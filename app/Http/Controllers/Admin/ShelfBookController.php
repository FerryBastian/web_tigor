<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShelfBook;
use Illuminate\Http\Request;

class ShelfBookController extends Controller
{
    public function index()
    {
        $shelfBooks = ShelfBook::orderBy('title')->paginate(20);
        return view('admin.shelf_books.index', compact('shelfBooks'));
    }

    public function create()
    {
        return view('admin.shelf_books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        ShelfBook::create($validated);

        return redirect()->route('admin.shelf-books.index')->with('success', 'Judul rak buku berhasil ditambahkan.');
    }

    public function edit(ShelfBook $shelfBook)
    {
        return view('admin.shelf_books.edit', compact('shelfBook'));
    }

    public function update(Request $request, ShelfBook $shelfBook)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $shelfBook->update($validated);

        return redirect()->route('admin.shelf-books.index')->with('success', 'Judul rak buku berhasil diperbarui.');
    }

    public function destroy(ShelfBook $shelfBook)
    {
        $shelfBook->delete();

        return redirect()->route('admin.shelf-books.index')->with('success', 'Judul rak buku berhasil dihapus.');
    }
}
