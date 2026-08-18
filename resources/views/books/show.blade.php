@extends('layouts.app')
@section('title', $book->title)
@section('page-title', 'Detail Buku')
@section('breadcrumb', 'Katalog → ' . Str::limit($book->title, 40))

@section('header-actions')
@auth
@if(auth()->user()->isAdmin())
<a href="{{ route('books.edit', $book) }}" class="flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2 rounded-xl transition">
    <i data-lucide="pencil" class="w-4 h-4"></i> Edit
</a>
@endif
@endauth
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Book Info Card --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex gap-6">
            {{-- Cover --}}
            <div class="w-32 h-44 rounded-xl flex items-center justify-center flex-shrink-0 overflow-hidden"
                 style="background: linear-gradient(135deg, {{ $book->category->color ?? '#6366f1' }}20, {{ $book->category->color ?? '#6366f1' }}40)">
                @if($book->cover_image)
                <img src="{{ Storage::url($book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                @else
                <i data-lucide="book" class="w-12 h-12 opacity-40" style="color: {{ $book->category->color ?? '#6366f1' }}"></i>
                @endif
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-start gap-2 flex-wrap mb-2">
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium"
                          style="background: {{ $book->category->color ?? '#6366f1' }}20; color: {{ $book->category->color ?? '#6366f1' }}">
                        {{ $book->category->name }}
                    </span>
                    <span class="text-xs px-2.5 py-1 rounded-full font-medium {{ $book->isAvailable() ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ $book->isAvailable() ? '✓ Tersedia (' . $book->available_copies . ' eks)' : '✗ Sedang Dipinjam' }}
                    </span>
                </div>

                <h2 class="text-xl font-bold text-slate-800 leading-tight mb-1">{{ $book->title }}</h2>
                <p class="text-slate-500 text-sm mb-4">Oleh <span class="font-medium text-slate-700">{{ $book->author }}</span></p>

                <div class="grid grid-cols-2 gap-y-2 text-sm">
                    @if($book->isbn)
                    <div><span class="text-slate-400">ISBN</span><p class="font-medium text-slate-700">{{ $book->isbn }}</p></div>
                    @endif
                    @if($book->publisher)
                    <div><span class="text-slate-400">Penerbit</span><p class="font-medium text-slate-700">{{ $book->publisher }}</p></div>
                    @endif
                    @if($book->publish_year)
                    <div><span class="text-slate-400">Tahun</span><p class="font-medium text-slate-700">{{ $book->publish_year }}</p></div>
                    @endif
                    @if($book->pages)
                    <div><span class="text-slate-400">Halaman</span><p class="font-medium text-slate-700">{{ $book->pages }} hal</p></div>
                    @endif
                    @if($book->shelf_location)
                    <div><span class="text-slate-400">Lokasi Rak</span><p class="font-medium text-slate-700">{{ $book->shelf_location }}</p></div>
                    @endif
                    <div><span class="text-slate-400">Total Eksemplar</span><p class="font-medium text-slate-700">{{ $book->total_copies }}</p></div>
                </div>
            </div>
        </div>

        @if($book->description)
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-3">Deskripsi</h3>
            <p class="text-slate-600 text-sm leading-relaxed">{{ $book->description }}</p>
        </div>
        @endif

        {{-- Admin only: current borrowers --}}
        @auth
        @if(auth()->user()->isAdmin() && $book->activeLoans->count())
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-semibold text-slate-800">Sedang Dipinjam Oleh</h3>
            </div>
            <div class="divide-y divide-slate-50">
                @foreach($book->activeLoans as $loan)
                <div class="flex items-center gap-3 px-5 py-3">
                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-xs font-bold text-primary-700">
                        {{ strtoupper(substr($loan->user->name,0,2)) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-slate-700">{{ $loan->user->name }}</p>
                        <p class="text-xs text-slate-400">Jatuh tempo: {{ $loan->due_date->format('d M Y') }}</p>
                    </div>
                    @if($loan->isOverdue())
                    <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full font-medium">Terlambat</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endauth
    </div>

    {{-- Actions Sidebar --}}
    <div class="space-y-4">
        @auth
        {{-- Admin Actions --}}
        @if(auth()->user()->isAdmin())
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Tindakan Admin</h3>
            <div class="space-y-2">
                <a href="{{ route('loans.create', ['book_id'=>$book->id]) }}"
                   class="w-full flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition justify-center">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i> Proses Peminjaman
                </a>
                <a href="{{ route('books.edit', $book) }}"
                   class="w-full flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium px-4 py-2.5 rounded-xl transition justify-center">
                    <i data-lucide="pencil" class="w-4 h-4"></i> Edit Data Buku
                </a>
                <form method="POST" action="{{ route('books.destroy', $book) }}"
                      onsubmit="return confirm('Yakin hapus buku ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium px-4 py-2.5 rounded-xl transition justify-center">
                        <i data-lucide="trash-2" class="w-4 h-4"></i> Hapus Buku
                    </button>
                </form>
            </div>
        </div>
        @else
        {{-- Member Actions --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Aksi</h3>
            @if(!$book->isAvailable())
            @php
            $hasReservation = \App\Models\Reservation::where('user_id', auth()->id())
                ->where('book_id', $book->id)->whereIn('status',['waiting','ready'])->exists();
            @endphp
            @if(!$hasReservation)
            <form method="POST" action="{{ route('reservations.store') }}">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">
                <button type="submit" class="w-full flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition justify-center">
                    <i data-lucide="bookmark" class="w-4 h-4"></i> Reservasi Buku Ini
                </button>
            </form>
            @else
            <div class="flex items-center gap-2 bg-amber-50 text-amber-700 text-sm p-3 rounded-xl">
                <i data-lucide="clock" class="w-4 h-4"></i> Anda sudah dalam antrean reservasi
            </div>
            @endif
            @else
            <div class="flex items-center gap-2 bg-emerald-50 text-emerald-700 text-sm p-3 rounded-xl">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                Buku tersedia, hubungi pustakawan untuk meminjam
            </div>
            @endif
        </div>
        @endif
        @else
        <div class="bg-primary-50 border border-primary-100 rounded-2xl p-5 text-center">
            <i data-lucide="user-circle" class="w-8 h-8 text-primary-400 mx-auto mb-2"></i>
            <p class="text-sm text-primary-700 font-medium mb-3">Login untuk meminjam atau mereservasi buku</p>
            <a href="{{ route('login') }}" class="text-sm bg-primary-600 text-white px-4 py-2 rounded-xl hover:bg-primary-700 transition">Masuk</a>
        </div>
        @endauth

        {{-- Book Stats --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Info Peminjaman</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Total dipinjam</span>
                    <span class="font-semibold text-slate-700">{{ $book->loans->count() }}×</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Antrean reservasi</span>
                    <span class="font-semibold text-slate-700">{{ $book->reservations->where('status','waiting')->count() }} orang</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Tersedia sekarang</span>
                    <span class="font-semibold {{ $book->isAvailable() ? 'text-emerald-600' : 'text-red-500' }}">{{ $book->available_copies }}/{{ $book->total_copies }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
