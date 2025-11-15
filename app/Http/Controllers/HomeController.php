<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Book;
use App\Models\Category;
use App\Services\GoogleBooksService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        private GoogleBooksService $googleBooksService
    ) {
    }

    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();
        $selectedCategory = $request->query('category');

        $localBooksQuery = Book::with('category')->latest();

        if ($selectedCategory) {
            $localBooksQuery->whereHas('category', function ($query) use ($selectedCategory) {
                $query->where('slug', $selectedCategory);
            });
        }

        $localBooks = $localBooksQuery->take(6)->get();

        // 5 kegiatan terbaru untuk carousel utama
        $carouselActivities = Activity::latest()->take(5)->get();

        // Kegiatan terbaru untuk section bawah
        $latestActivities = Activity::latest()->take(6)->get();

        return view('home', [
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'localBooks' => $localBooks,
            'carouselActivities' => $carouselActivities,
            'latestActivities' => $latestActivities,
        ]);
    }
}
