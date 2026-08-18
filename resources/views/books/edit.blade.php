@extends('layouts.app')
@section('title', 'Edit Buku')
@section('page-title', 'Edit Buku')
@section('breadcrumb', 'Katalog → ' . Str::limit($book->title, 40) . ' → Edit')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data" class="space-y-5">
        @csrf @method('PUT')
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-5 pb-3 border-b border-slate-50">Edit Data Buku</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $book->title) }}" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Penulis <span class="text-red-500">*</span></label>
                    <input type="text" name="author" value="{{ old('author', $book->author) }}" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn', $book->isbn) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $book->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Penerbit</label>
                    <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Tahun Terbit</label>
                    <input type="number" name="publish_year" value="{{ old('publish_year', $book->publish_year) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Total Eksemplar <span class="text-red-500">*</span></label>
                    <input type="number" name="total_copies" value="{{ old('total_copies', $book->total_copies) }}" min="1" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Lokasi Rak</label>
                    <input type="text" name="shelf_location" value="{{ old('shelf_location', $book->shelf_location) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Halaman</label>
                    <input type="number" name="pages" value="{{ old('pages', $book->pages) }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="4" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50 resize-none">{{ old('description', $book->description) }}</textarea>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Cover Buku</label>
                    @if($book->cover_image)
                    <div class="mb-3 flex items-center gap-3">
                        <img src="{{ Storage::url($book->cover_image) }}" class="w-16 h-20 object-cover rounded-lg border">
                        <span class="text-xs text-slate-400">Cover saat ini</span>
                    </div>
                    @endif
                    <input type="file" name="cover_image" accept="image/*"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm bg-slate-50 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-primary-50 file:text-primary-700">
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl transition">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
            </button>
            <a href="{{ route('books.show', $book) }}" class="flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium px-6 py-2.5 rounded-xl transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
