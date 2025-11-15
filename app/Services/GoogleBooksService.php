<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GoogleBooksService
{
    /**
     * Keywords that must appear to keep the book.
     */
    protected array $requiredKeywords = [
        'pelajaran',
        'sekolah',
        'sejarah',
        'pengetahuan umum',
        'edukasi',
        'pendidikan',
    ];

    /**
     * Keywords that will exclude a book (case-insensitive).
     */
    protected array $excludedKeywords = [
        'islam',
        'muslim',
        'alquran',
        'quran',
        'alqur\'an',
        'tasawuf',
        'fiqh',
        'fikih',
        'syariah',
        'aqidah',
        'akidah',
        'hadis',
        'hadith',
        'tafsir',
        'ushuluddin',
    ];
    /**
     * Fetch educational themed books in Indonesian from Google Books.
     */
    public function fetchEducationalVolumes(int $maxResults = 10): array
    {
        try {
            // Gunakan startIndex acak agar hasil tidak selalu sama
            $startIndex = random_int(0, 30);

            $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                'langRestrict' => 'id',
                'q' => 'pelajaran|pengetahuan umum|sejarah|buku sekolah',
                'maxResults' => $maxResults,
                'startIndex' => $startIndex,
            ]);

            if ($response->failed()) {
                Log::warning('Google Books API returned a failed response.', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return [];
            }

            return collect($response->json('items', []))
                ->map(function (array $item) {
                    $volumeInfo = $item['volumeInfo'] ?? [];

                    return [
                        'google_id' => $item['id'] ?? null,
                        'title' => $volumeInfo['title'] ?? 'Tanpa Judul',
                        'authors' => isset($volumeInfo['authors'])
                            ? implode(', ', $volumeInfo['authors'])
                            : 'Penulis tidak diketahui',
                        'description' => $volumeInfo['description'] ?? 'Deskripsi belum tersedia.',
                        'thumbnail' => isset($volumeInfo['imageLinks']['thumbnail'])
                            ? str_replace('http://', 'https://', $volumeInfo['imageLinks']['thumbnail'])
                            : null,
                        'preview_link' => $volumeInfo['previewLink'] ?? null,
                        'info_link' => $volumeInfo['infoLink'] ?? null,
                        'raw' => $item,
                    ];
                })
                ->filter(fn (array $book) => !empty($book['google_id']))
                ->filter(fn (array $book) => $this->passesKeywordFilter($book))
                ->values()
                ->all();
        } catch (\Throwable $exception) {
            Log::error('Failed to call Google Books API.', [
                'message' => $exception->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Fetch a single Google Books volume in detail.
     */
    public function fetchVolumeDetail(string $volumeId): ?array
    {
        try {
            $response = Http::get("https://www.googleapis.com/books/v1/volumes/{$volumeId}");

            if ($response->failed()) {
                Log::warning('Google Books API volume detail failed.', [
                    'status' => $response->status(),
                    'volume_id' => $volumeId,
                ]);

                return null;
            }

            $data = $response->json();
            if (empty($data['volumeInfo'])) {
                return null;
            }

            $volumeInfo = $data['volumeInfo'];

            $book = [
                'google_id' => $data['id'] ?? $volumeId,
                'title' => $volumeInfo['title'] ?? 'Tanpa Judul',
                'subtitle' => $volumeInfo['subtitle'] ?? null,
                'authors' => isset($volumeInfo['authors'])
                    ? implode(', ', $volumeInfo['authors'])
                    : 'Penulis tidak diketahui',
                'description' => $volumeInfo['description'] ?? 'Deskripsi belum tersedia.',
                'thumbnail' => isset($volumeInfo['imageLinks']['large'])
                    ? str_replace('http://', 'https://', $volumeInfo['imageLinks']['large'])
                    : (isset($volumeInfo['imageLinks']['thumbnail'])
                        ? str_replace('http://', 'https://', $volumeInfo['imageLinks']['thumbnail'])
                        : null),
                'preview_link' => $volumeInfo['previewLink'] ?? null,
                'info_link' => $volumeInfo['infoLink'] ?? null,
                'categories' => $volumeInfo['categories'] ?? [],
                'publisher' => $volumeInfo['publisher'] ?? null,
                'language' => $volumeInfo['language'] ?? null,
                'page_count' => $volumeInfo['pageCount'] ?? null,
                'published_at' => $volumeInfo['publishedDate'] ?? null,
                'embed_link' => isset($data['id'])
                    ? "https://books.google.com/books?id={$data['id']}&printsec=frontcover&output=embed"
                    : null,
            ];

            if (! $this->passesKeywordFilter($book)) {
                return null;
            }

            return $book;
        } catch (\Throwable $exception) {
            Log::error('Failed to fetch Google Books volume detail.', [
                'message' => $exception->getMessage(),
                'volume_id' => $volumeId,
            ]);

            return null;
        }
    }

    /**
     * Fetch novel-themed books (Indonesian and English).
     */
    public function fetchNovelVolumes(int $maxResults = 10): array
    {
        try {
            $items = collect();

            foreach (['id', 'en'] as $lang) {
                $startIndex = random_int(0, 40);

                $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                    'langRestrict' => $lang,
                    'q' => 'novel|fiction',
                    'maxResults' => $maxResults,
                    'startIndex' => $startIndex,
                ]);

                if ($response->successful()) {
                    $items = $items->merge($response->json('items', []));
                }
            }

            return $items
                ->map(function (array $item) {
                    $volumeInfo = $item['volumeInfo'] ?? [];

                    return [
                        'google_id' => $item['id'] ?? null,
                        'title' => $volumeInfo['title'] ?? 'Tanpa Judul',
                        'authors' => isset($volumeInfo['authors'])
                            ? implode(', ', $volumeInfo['authors'])
                            : 'Penulis tidak diketahui',
                        'description' => $volumeInfo['description'] ?? 'Deskripsi belum tersedia.',
                        'thumbnail' => isset($volumeInfo['imageLinks']['thumbnail'])
                            ? str_replace('http://', 'https://', $volumeInfo['imageLinks']['thumbnail'])
                            : null,
                        'preview_link' => $volumeInfo['previewLink'] ?? null,
                        'info_link' => $volumeInfo['infoLink'] ?? null,
                        'raw' => $item,
                    ];
                })
                ->filter(fn (array $book) => !empty($book['google_id']))
                ->filter(fn (array $book) => $this->passesKeywordFilter($book))
                ->unique(fn (array $book) => $book['google_id'])
                ->values()
                ->take($maxResults)
                ->all();
        } catch (\Throwable $exception) {
            Log::error('Failed to call Google Books API for novels.', [
                'message' => $exception->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Fetch children comics in Indonesian.
     */
    public function fetchComicVolumes(int $maxResults = 10): array
    {
        try {
            $startIndex = random_int(0, 40);

            $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
                'langRestrict' => 'id',
                'q' => 'komik anak|komik anak-anak|komik pendidikan',
                'maxResults' => $maxResults,
                'startIndex' => $startIndex,
            ]);

            if ($response->failed()) {
                Log::warning('Google Books API returned a failed response for comics.', [
                    'status' => $response->status(),
                    'body' => $response->json(),
                ]);

                return [];
            }

            return collect($response->json('items', []))
                ->map(function (array $item) {
                    $volumeInfo = $item['volumeInfo'] ?? [];

                    return [
                        'google_id' => $item['id'] ?? null,
                        'title' => $volumeInfo['title'] ?? 'Tanpa Judul',
                        'authors' => isset($volumeInfo['authors'])
                            ? implode(', ', $volumeInfo['authors'])
                            : 'Penulis tidak diketahui',
                        'description' => $volumeInfo['description'] ?? 'Deskripsi belum tersedia.',
                        'thumbnail' => isset($volumeInfo['imageLinks']['thumbnail'])
                            ? str_replace('http://', 'https://', $volumeInfo['imageLinks']['thumbnail'])
                            : null,
                        'preview_link' => $volumeInfo['previewLink'] ?? null,
                        'info_link' => $volumeInfo['infoLink'] ?? null,
                        'raw' => $item,
                    ];
                })
                ->filter(fn (array $book) => !empty($book['google_id']))
                ->filter(fn (array $book) => $this->passesKeywordFilter($book))
                ->unique(fn (array $book) => $book['google_id'])
                ->values()
                ->take($maxResults)
                ->all();
        } catch (\Throwable $exception) {
            Log::error('Failed to call Google Books API for comics.', [
                'message' => $exception->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Ensure the book matches required keywords and excludes forbidden ones.
     */
    protected function passesKeywordFilter(array $book): bool
    {
        $combined = Str::lower(($book['title'] ?? '') . ' ' . ($book['description'] ?? ''));

        foreach ($this->excludedKeywords as $excluded) {
            if (Str::contains($combined, $excluded)) {
                return false;
            }
        }

        // Jika mengandung minimal satu required keyword, langsung lolos
        foreach ($this->requiredKeywords as $required) {
            if (Str::contains($combined, $required)) {
                return true;
            }
        }

        // Jika tidak mengandung required keywords, tetap diterima
        // selama tidak mengandung excluded keywords di atas.
        return true;
    }
}

