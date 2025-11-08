<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        $recentBooks = Book::latest()->take(5)->get();
        
        return view('admin.dashboard', compact('totalBooks', 'recentBooks'));
    }

    public function importGoogleBooks()
    {
        try {
            // Query untuk buku populer - menggunakan beberapa query berbeda untuk variasi
            $queries = [
                'bestseller',
                'popular fiction',
                'classic literature',
                'science fiction',
                'mystery',
            ];

            $importedCount = 0;
            $booksPerQuery = 2; // Ambil 2 buku per query untuk total ~10 buku

            foreach ($queries as $query) {
                $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                    'q' => $query,
                    'maxResults' => $booksPerQuery,
                    'orderBy' => 'relevance',
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['items'])) {
                        foreach ($data['items'] as $item) {
                            $volumeInfo = $item['volumeInfo'] ?? [];
                            
                            // Skip jika buku sudah ada (berdasarkan title)
                            $title = $volumeInfo['title'] ?? 'Untitled';
                            if (Book::where('title', $title)->exists()) {
                                continue;
                            }

                            // Extract data
                            $authors = $volumeInfo['authors'] ?? [];
                            $author = !empty($authors) ? implode(', ', $authors) : 'Unknown Author';
                            $description = $volumeInfo['description'] ?? 'No description available.';
                            $coverUrl = null;

                            // Get cover image
                            if (isset($volumeInfo['imageLinks']['thumbnail'])) {
                                $coverUrl = str_replace('http://', 'https://', $volumeInfo['imageLinks']['thumbnail']);
                            } elseif (isset($volumeInfo['imageLinks']['smallThumbnail'])) {
                                $coverUrl = str_replace('http://', 'https://', $volumeInfo['imageLinks']['smallThumbnail']);
                            }

                            // Create book
                            Book::create([
                                'title' => $title,
                                'author' => $author,
                                'description' => $description,
                                'cover_url' => $coverUrl,
                            ]);

                            $importedCount++;
                        }
                    }
                }
            }

            return redirect()->route('admin.dashboard')
                ->with('success', "Successfully imported {$importedCount} books from Google Books API!");
                
        } catch (\Exception $e) {
            Log::error('Google Books API import error: ' . $e->getMessage());
            
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to import books from Google Books API. Please try again later.');
        }
    }
}
