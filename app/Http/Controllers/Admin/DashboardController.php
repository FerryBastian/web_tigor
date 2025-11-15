<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Book;
use App\Models\Category;
use App\Services\GoogleBooksService;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct(
        private GoogleBooksService $googleBooksService
    ) {
    }

    public function index()
    {
        $totalBooks = Book::count();
        $totalCategories = Category::count();
        $totalActivities = Activity::count();
        $recentBooks = Book::latest()->take(5)->get();
        $recentActivities = Activity::latest()->take(3)->get();
        
        return view('admin.dashboard', compact('totalBooks', 'totalCategories', 'totalActivities', 'recentBooks', 'recentActivities'));
    }

    public function importGoogleBooks()
    {
        try {
            $importedCount = 0;
            // Ambil lebih banyak kandidat buku dari Google Books
            $volumes = $this->googleBooksService->fetchEducationalVolumes(40);
            $defaultCategory = Category::firstOrCreate(['name' => 'Pengetahuan Umum']);

            // Acak urutan agar variasi buku lebih tinggi
            $volumes = collect($volumes)->shuffle();

            foreach ($volumes as $volume) {
                $title = trim($volume['title'] ?? 'Tanpa Judul');

                $author = $volume['authors'] ?? 'Penulis tidak diketahui';
                $description = $volume['description'] ?? 'Deskripsi belum tersedia.';
                $coverUrl = $volume['thumbnail'] ?? null;
                $previewLink = $volume['preview_link'] ?? $volume['info_link'] ?? null;

                // Jangan simpan buku yang sama (title+author sudah ada di database)
                if (Book::where('title', $title)->where('author', $author)->exists()) {
                    continue;
                }

                Book::create([
                    'title' => $title,
                    'author' => $author,
                    'description' => $description,
                    'cover_url' => $coverUrl,
                    'preview_link' => $previewLink,
                    'category_id' => $defaultCategory->id,
                ]);

                $importedCount++;

                // Batasi jumlah buku baru per sekali impor
                if ($importedCount >= 10) {
                    break;
                }
            }

            return redirect()->route('admin.dashboard')
                ->with('success', "Berhasil mengimpor {$importedCount} buku dari Google Books API.");
                
        } catch (\Exception $e) {
            Log::error('Google Books API import error: ' . $e->getMessage());
            
            return redirect()->route('admin.dashboard')
                ->with('error', 'Gagal mengimpor buku dari Google Books API. Silakan coba lagi nanti.');
        }
    }

    public function importGoogleNovels()
    {
        try {
            $importedCount = 0;
            $volumes = $this->googleBooksService->fetchNovelVolumes(40);
            $defaultCategory = Category::firstOrCreate(['name' => 'Novel']);

            $volumes = collect($volumes)->shuffle();

            foreach ($volumes as $volume) {
                $title = trim($volume['title'] ?? 'Tanpa Judul');
                $author = $volume['authors'] ?? 'Penulis tidak diketahui';
                $description = $volume['description'] ?? 'Deskripsi belum tersedia.';
                $coverUrl = $volume['thumbnail'] ?? null;
                $previewLink = $volume['preview_link'] ?? $volume['info_link'] ?? null;

                if (Book::where('title', $title)->where('author', $author)->exists()) {
                    continue;
                }

                Book::create([
                    'title' => $title,
                    'author' => $author,
                    'description' => $description,
                    'cover_url' => $coverUrl,
                    'preview_link' => $previewLink,
                    'category_id' => $defaultCategory->id,
                ]);

                $importedCount++;

                if ($importedCount >= 10) {
                    break;
                }
            }

            return redirect()->route('admin.dashboard')
                ->with('success', "Berhasil mengimpor {$importedCount} novel dari Google Books API.");
        } catch (\Exception $e) {
            Log::error('Google Books API import novels error: ' . $e->getMessage());

            return redirect()->route('admin.dashboard')
                ->with('error', 'Gagal mengimpor novel dari Google Books API. Silakan coba lagi nanti.');
        }
    }

    public function importGoogleComics()
    {
        try {
            $importedCount = 0;
            $volumes = $this->googleBooksService->fetchComicVolumes(40);
            $defaultCategory = Category::firstOrCreate(['name' => 'Komik Anak']);

            $volumes = collect($volumes)->shuffle();

            foreach ($volumes as $volume) {
                $title = trim($volume['title'] ?? 'Tanpa Judul');
                $author = $volume['authors'] ?? 'Penulis tidak diketahui';
                $description = $volume['description'] ?? 'Deskripsi belum tersedia.';
                $coverUrl = $volume['thumbnail'] ?? null;
                $previewLink = $volume['preview_link'] ?? $volume['info_link'] ?? null;

                if (Book::where('title', $title)->where('author', $author)->exists()) {
                    continue;
                }

                Book::create([
                    'title' => $title,
                    'author' => $author,
                    'description' => $description,
                    'cover_url' => $coverUrl,
                    'preview_link' => $previewLink,
                    'category_id' => $defaultCategory->id,
                ]);

                $importedCount++;

                if ($importedCount >= 10) {
                    break;
                }
            }

            return redirect()->route('admin.dashboard')
                ->with('success', "Berhasil mengimpor {$importedCount} komik anak dari Google Books API.");
        } catch (\Exception $e) {
            Log::error('Google Books API import comics error: ' . $e->getMessage());

            return redirect()->route('admin.dashboard')
                ->with('error', 'Gagal mengimpor komik anak dari Google Books API. Silakan coba lagi nanti.');
        }
    }
}
