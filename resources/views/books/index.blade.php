@extends('layouts.app')
@section('title','Katalog Buku')
@section('page-title','Katalog Buku')
@section('breadcrumb','OPAC — Pencarian & Browse Koleksi')

@section('header-actions')
@auth @if(auth()->user()->isAdmin())
<a href="{{ route('books.create') }}" class="btn-primary">
    <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah Buku
</a>
@endif @endauth
@endsection

@section('content')

{{-- Search & Filter --}}
<div class="card p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3">
        <div class="flex-1 min-w-56 relative">
            <i data-lucide="search" class="w-4 h-4 text-surface-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="form-input pl-10" placeholder="Cari judul, penulis, ISBN…">
        </div>
        <select name="category" class="form-select w-auto min-w-36">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="availability" class="form-select w-auto min-w-36">
            <option value="">Semua Status</option>
            <option value="available" {{ request('availability')==='available'?'selected':'' }}>Tersedia</option>
            <option value="borrowed"  {{ request('availability')==='borrowed'?'selected':'' }}>Dipinjam</option>
        </select>
        <button type="submit" class="btn-primary">Cari</button>
        @if(request()->hasAny(['search','category','availability']))
        <a href="{{ route('books.index') }}" class="btn-ghost">
            <i data-lucide="x" class="w-3.5 h-3.5"></i> Reset
        </a>
        @endif
    </form>
</div>

{{-- Count --}}
<div class="flex items-center justify-between mb-4">
    <p class="text-xs text-surface-500 font-medium">
        <span class="font-bold text-surface-800">{{ $books->total() }}</span> buku ditemukan
    </p>
</div>

{{-- Grid --}}
@if($books->count())
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-4 mb-6">
    @foreach($books as $book)
    <a href="{{ route('books.show',$book) }}"
       class="card group hover:shadow-card-md hover:-translate-y-1 transition-all duration-200 overflow-hidden block">
        {{-- Cover --}}
        <div class="h-44 relative overflow-hidden"
             style="background:linear-gradient(135deg,{{ $book->category->color??'#4361f0' }}18,{{ $book->category->color??'#4361f0' }}35)">
            @if($book->cover_image)
            <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
            @else
            <div class="absolute inset-0 flex items-center justify-center">
                <i data-lucide="book" class="w-14 h-14 opacity-20" style="color:{{ $book->category->color??'#4361f0' }}"></i>
            </div>
            {{-- Abstract decoration --}}
            <div class="absolute -bottom-4 -right-4 w-20 h-20 rounded-full opacity-20"
                 style="background:{{ $book->category->color??'#4361f0' }}"></div>
            @endif
            {{-- Availability badge --}}
            <div class="absolute top-2.5 right-2.5">
                <span class="badge shadow-sm {{ $book->isAvailable() ? 'bg-emerald-500 text-white' : 'bg-surface-800/80 text-white' }}">
                    {{ $book->isAvailable() ? '✓' : '✗' }}
                </span>
            </div>
        </div>

        {{-- Info --}}
        <div class="p-3">
            <p class="text-[12px] font-bold text-surface-800 line-clamp-2 leading-tight mb-1">{{ $book->title }}</p>
            <p class="text-[11px] text-surface-400 truncate mb-2">{{ $book->author }}</p>
            <div class="flex items-center justify-between">
                <span class="badge text-[10px]"
                      style="background:{{ $book->category->color??'#4361f0' }}15;color:{{ $book->category->color??'#4361f0' }}">
                    {{ $book->category->name??'-' }}
                </span>
                <span class="text-[10px] text-surface-400">{{ $book->available_copies }}/{{ $book->total_copies }}</span>
            </div>
        </div>
    </a>
    @endforeach
</div>
{{ $books->links() }}

@else
<div class="card p-16 text-center">
    <div class="w-16 h-16 rounded-3xl bg-surface-100 flex items-center justify-center mx-auto mb-4">
        <i data-lucide="search-x" class="w-8 h-8 text-surface-400"></i>
    </div>
    <h3 class="font-bold text-surface-700 mb-1">Buku Tidak Ditemukan</h3>
    <p class="text-sm text-surface-400">Coba kata kunci pencarian yang berbeda</p>
</div>
@endif

@endsection
