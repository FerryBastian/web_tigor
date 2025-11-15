<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\ShelfBook;
use App\Services\GoogleBooksService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon; // Ensure Carbon is available for API book date mocking
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function __construct(
        private GoogleBooksService $googleBooksService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('category')->latest()->paginate(15);
        return view('admin.books.index', compact('books'));
    }

    /**
     * Display the public catalog of books.
     */
    public function catalog(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $categorySlug = $request->query('category');

        $booksQuery = Book::with('category')->latest();

        if ($categorySlug) {
            $booksQuery->whereHas('category', function ($query) use ($categorySlug) {
                $query->where('slug', $categorySlug);
            });
        }

        $books = $booksQuery->paginate(12)->withQueryString();
        $recommendedBooks = collect($this->googleBooksService->fetchEducationalVolumes());

        $selectedCategory = $categorySlug;

        $shelfBooks = ShelfBook::orderBy('title')->get();

        return view('books.index', compact('books', 'categories', 'categorySlug', 'selectedCategory', 'recommendedBooks', 'shelfBooks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.books.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
            'cover_url' => 'nullable|url',
            'preview_link' => 'nullable|url',
            'category_id' => 'required|exists:categories,id',
        ]);

        // 🔍 Ambil data tambahan otomatis dari Google Books API
        $apiUrl = 'https://www.googleapis.com/books/v1/volumes?q=' . urlencode($validated['title']);
        $response = Http::get($apiUrl);

        if ($response->successful() && isset($response['items'][0])) {
            $item = $response['items'][0]['volumeInfo'];

            if (empty($validated['cover_url']) && isset($item['imageLinks']['thumbnail'])) {
                $validated['cover_url'] = str_replace('http://', 'https://', $item['imageLinks']['thumbnail']);
            }

            if (isset($item['previewLink'])) {
                $validated['preview_link'] = $item['previewLink'];
            }
        }

        if (empty($validated['preview_link'])) {
            // fallback ke Open Library
            $query = urlencode($validated['title'] . ' ' . $validated['author']);
            $olResponse = Http::get("https://openlibrary.org/search.json?title={$query}&limit=1");

            if ($olResponse->ok() && isset($olResponse['docs'][0])) {
                $doc = $olResponse['docs'][0];
                if (isset($doc['key'])) {
                    $validated['preview_link'] = 'https://openlibrary.org' . $doc['key'];
                }
                if (empty($validated['cover_url']) && isset($doc['cover_i'])) {
                    $validated['cover_url'] = "https://covers.openlibrary.org/b/id/{$doc['cover_i']}-L.jpg";
                }
            }
        }

        $validated['cover_url'] = $validated['cover_url'] ?? null;
        $validated['preview_link'] = $validated['preview_link'] ?? null;

        Book::create($validated);

        return redirect()->route('admin.books.index')->with('success', 'Book created successfully with API data.');
    }


    /**
     * Display the specified resource.
     * Handles both local DB ID (numeric) or Open Library Work ID (string).
     */
    public function show(string $id)
    {
        $additionalDetails = [];
        $embedLink = null;

        if (is_numeric($id)) {
            // --- CASE 1: LOCAL BOOK (Numeric ID) ---
            
            $book = Book::with('category')->findOrFail($id);
            $previewLink = $book->preview_link ?? null;
            $coverApi = $book->cover_url ?? null;

            // Fallback to search Open Library if no preview_link exists locally
            if (!$previewLink) {
                $apiUrl = 'https://openlibrary.org/search.json?title=' . urlencode($book->title);
                $response = Http::get($apiUrl); // Using Http facade
                
                if ($response->successful() && isset($response->json()['docs'][0])) {
                    $doc = $response->json()['docs'][0];
                    if (isset($doc['key'])) {
                        $previewLink = "https://openlibrary.org" . $doc['key'];
                    }
                    if (isset($doc['cover_i']) && !$coverApi) {
                        $coverApi = "https://covers.openlibrary.org/b/id/{$doc['cover_i']}-L.jpg";
                    }
                }
            }
            
            // Jika previewLink adalah link Google Books, coba bentuk URL embed yang valid
            $embedLink = $previewLink;
            if ($previewLink && Str::contains($previewLink, 'books.google.')) {
                $parsed = parse_url($previewLink);
                $embedId = null;

                if (!empty($parsed['query'])) {
                    parse_str($parsed['query'], $queryParams);
                    $embedId = $queryParams['id'] ?? null;
                }

                if ($embedId) {
                    $embedLink = "https://books.google.com/books?id={$embedId}&printsec=frontcover&output=embed";
                }
            }
            
            return view('book.show', compact('book', 'previewLink', 'coverApi', 'additionalDetails', 'embedLink'));

        } elseif (Str::startsWith($id, 'google-')) {
            // --- CASE 2: GOOGLE BOOK (Volume ID) ---

            $volumeId = Str::after($id, 'google-');
            $volume = $this->googleBooksService->fetchVolumeDetail($volumeId);

            if (! $volume) {
                abort(404, 'Detail buku Google tidak ditemukan atau tidak sesuai kriteria.');
            }

            $coverApi = $volume['thumbnail'] ?? "https://via.placeholder.com/400x600?text=No+Cover";
            $previewLink = $volume['preview_link'] ?? $volume['info_link'] ?? null;
            $embedLink = $volume['embed_link'] ?? $previewLink;

            $subtitle = $volume['subtitle'] ?? null;

            $book = (object) [
                'id' => $volume['google_id'],
                'title' => trim(($volume['title'] ?? 'Tanpa Judul') . ($subtitle ? ' - ' . $subtitle : '')),
                'author' => $volume['authors'],
                'description' => $volume['description'],
                'cover_url' => $coverApi,
                'category' => null,
                'created_at' => Carbon::now(),
            ];

            $additionalDetails = array_filter([
                'Kategori' => !empty($volume['categories']) ? implode(', ', $volume['categories']) : null,
                'Penerbit' => $volume['publisher'] ?? null,
                'Tanggal Terbit' => $volume['published_at'] ?? null,
                'Jumlah Halaman' => !empty($volume['page_count']) ? $volume['page_count'] . ' halaman' : null,
                'Bahasa' => !empty($volume['language']) ? Str::upper($volume['language']) : null,
            ]);

            return view('book.show', compact('book', 'previewLink', 'coverApi', 'additionalDetails', 'embedLink'));

        } else {
            // --- CASE 2: API BOOK (Open Library Work ID like 'OL...W') ---
            
            $workId = $id;
            $workApiUrl = "https://openlibrary.org/works/{$workId}.json";
            $workResponse = Http::get($workApiUrl);

            if ($workResponse->failed() || !isset($workResponse->json()['title'])) {
                abort(404, 'Book details not found on Open Library or bad key.');
            }

            $workData = $workResponse->json();

            // Fetch author name (requires an extra API call)
            $authorName = 'Unknown Author';
            if (isset($workData['authors'][0]['author']['key'])) {
                $authorKey = $workData['authors'][0]['author']['key'];
                $authorResponse = Http::get("https://openlibrary.org{$authorKey}.json");
                if ($authorResponse->successful()) {
                    $authorName = $authorResponse->json()['name'] ?? 'Unknown Author';
                }
            }

            // Get cover image URL
            $coverApi = "https://via.placeholder.com/400x600?text=No+Cover";
            if (isset($workData['covers'][0]) && $workData['covers'][0] != -1) {
                $coverApi = "https://covers.openlibrary.org/b/id/{$workData['covers'][0]}-L.jpg";
            }

            // Get description (handling array/object format)
            $description = 'No description available.';
            if (isset($workData['description'])) {
                $description = is_array($workData['description']) 
                                ? ($workData['description']['value'] ?? $description) 
                                : ($workData['description'] ?? $description);
            }

            // Create a VIRTUAL object for the view, mocking Eloquent properties for compatibility
            $book = (object) [
                'id' => $workId, 
                'title' => $workData['title'],
                'author' => $authorName,
                'description' => $description,
                'cover_url' => $coverApi,
                'category' => null,
                // Mock properties required by the view to prevent crashes
                'created_at' => Carbon::now(), 
                'preview_link' => null, 
            ];
            
            // The actual Open Library page link will be the preview link
            $previewLink = "https://openlibrary.org/works/{$workId}";

            $embedLink = $previewLink;

            return view('book.show', compact('book', 'previewLink', 'coverApi', 'additionalDetails', 'embedLink'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('admin.books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'required|string',
            'cover_url' => 'nullable|url',
            'preview_link' => 'nullable|url',
            'category_id' => 'required|exists:categories,id',
        ]);

        $book = Book::findOrFail($id);

        // perbarui data Google Books saat edit
        $apiUrl = 'https://www.googleapis.com/books/v1/volumes?q=' . urlencode($validated['title']);
        $response = Http::get($apiUrl);

        if ($response->successful() && isset($response['items'][0])) {
            $item = $response['items'][0]['volumeInfo'];

            if (empty($validated['cover_url']) && isset($item['imageLinks']['thumbnail'])) {
                $validated['cover_url'] = str_replace('http://', 'https://', $item['imageLinks']['thumbnail']);
            }

            if (isset($item['previewLink'])) {
                $validated['preview_link'] = $item['previewLink'];
            }
        }

        $validated['cover_url'] = $validated['cover_url'] ?? null;
        $validated['preview_link'] = $validated['preview_link'] ?? null;

        $book->update($validated);

        return redirect()->route('admin.books.index')->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully.');
    }
}