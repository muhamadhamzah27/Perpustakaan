@extends('layouts.app')
@section('title', 'Tambah Buku')
@section('page-title', 'Tambah Buku Baru')
@section('breadcrumb', 'Katalog → Tambah Buku')

@section('content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('books.store') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-5 pb-3 border-b border-slate-50">Informasi Buku</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul Buku <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50"
                           placeholder="Masukkan judul buku">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Penulis <span class="text-red-500">*</span></label>
                    <input type="text" name="author" value="{{ old('author') }}" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50"
                           placeholder="Nama penulis">
                    @error('author')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">ISBN</label>
                    <input type="text" name="isbn" value="{{ old('isbn') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50"
                           placeholder="978-xxx-xxx-xxxx">
                    @error('isbn')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
                    <select name="category_id" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                        <option value="">Pilih kategori</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Penerbit</label>
                    <input type="text" name="publisher" value="{{ old('publisher') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50"
                           placeholder="Nama penerbit">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Tahun Terbit</label>
                    <input type="number" name="publish_year" value="{{ old('publish_year') }}" min="1800" max="{{ date('Y') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50"
                           placeholder="{{ date('Y') }}">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Jumlah Eksemplar <span class="text-red-500">*</span></label>
                    <input type="number" name="total_copies" value="{{ old('total_copies', 1) }}" min="1" required
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Lokasi Rak</label>
                    <input type="text" name="shelf_location" value="{{ old('shelf_location') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50"
                           placeholder="Contoh: A-01">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Jumlah Halaman</label>
                    <input type="number" name="pages" value="{{ old('pages') }}" min="1"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Bahasa</label>
                    <select name="language" class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                        @foreach(['Indonesia','English','Arabic','Mandarin','Jawa','Sunda'] as $lang)
                        <option value="{{ $lang }}" {{ old('language','Indonesia') === $lang ? 'selected':'' }}>{{ $lang }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" rows="4"
                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50 resize-none"
                              placeholder="Sinopsis atau deskripsi buku">{{ old('description') }}</textarea>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Cover Buku</label>
                    <input type="file" name="cover_image" accept="image/*"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none bg-slate-50 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700">
                    <p class="text-xs text-slate-400 mt-1">PNG, JPG, max 2MB</p>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl transition shadow-sm">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Buku
            </button>
            <a href="{{ route('books.index') }}" class="flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium px-6 py-2.5 rounded-xl transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
