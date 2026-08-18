<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('isbn', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('available_copies', '>', 0);
            } else {
                $query->where('available_copies', 0);
            }
        }

        $books      = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::all();

        return view('books.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        $book->load(['category', 'loans.user', 'reservations.user']);
        return view('books.show', compact('book'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'author'         => 'required|string|max:255',
            'isbn'           => 'nullable|string|unique:books,isbn|max:20',
            'category_id'    => 'required|exists:categories,id',
            'publisher'      => 'nullable|string|max:255',
            'publish_year'   => 'nullable|integer|min:1800|max:' . date('Y'),
            'total_copies'   => 'required|integer|min:1',
            'shelf_location' => 'nullable|string|max:20',
            'description'    => 'nullable|string',
            'cover_image'    => 'nullable|image|max:2048',
            'language'       => 'nullable|string|max:50',
            'pages'          => 'nullable|integer|min:1',
        ]);

        $data['available_copies'] = $data['total_copies'];

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'author'         => 'required|string|max:255',
            'isbn'           => 'nullable|string|max:20|unique:books,isbn,' . $book->id,
            'category_id'    => 'required|exists:categories,id',
            'publisher'      => 'nullable|string|max:255',
            'publish_year'   => 'nullable|integer|min:1800|max:' . date('Y'),
            'total_copies'   => 'required|integer|min:1',
            'shelf_location' => 'nullable|string|max:20',
            'description'    => 'nullable|string',
            'cover_image'    => 'nullable|image|max:2048',
            'language'       => 'nullable|string|max:50',
            'pages'          => 'nullable|integer|min:1',
        ]);

        // Adjust available copies if total changed
        $diff = $data['total_copies'] - $book->total_copies;
        $data['available_copies'] = max(0, $book->available_copies + $diff);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $book->update($data);

        return redirect()->route('books.show', $book)->with('success', 'Data buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        if ($book->activeLoans()->exists()) {
            return back()->with('error', 'Tidak dapat menghapus buku yang sedang dipinjam.');
        }

        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }
}
