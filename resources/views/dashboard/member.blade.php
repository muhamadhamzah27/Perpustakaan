@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','Dashboard')
@section('breadcrumb','Halo, ' . auth()->user()->name . ' 👋')

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
    $cards = [
        ['label'=>'Dipinjam',      'value'=>$activeLoans->count(), 'icon'=>'book-open',    'bg'=>'bg-primary-50',  'color'=>'text-primary-600'],
        ['label'=>'Total Pinjaman','value'=>$totalBorrowed,        'icon'=>'history',       'bg'=>'bg-sky-50',      'color'=>'text-sky-600'],
        ['label'=>'Reservasi',     'value'=>$totalReservations,    'icon'=>'bookmark',      'bg'=>'bg-amber-50',    'color'=>'text-amber-600'],
        ['label'=>'Denda',         'value'=>'Rp '.number_format($totalFines,0,',','.'), 'icon'=>'banknote', 'bg'=>'bg-rose-50','color'=>'text-rose-600'],
    ];
    @endphp
    @foreach($cards as $i => $c)
    <div class="card p-5 animate-fade-up" style="animation-delay:{{ $i*.06 }}s">
        <div class="w-10 h-10 {{ $c['bg'] }} {{ $c['color'] }} rounded-2xl flex items-center justify-center mb-4">
            <i data-lucide="{{ $c['icon'] }}" class="w-5 h-5" stroke-width="2"></i>
        </div>
        <p class="text-xl font-extrabold text-surface-900 leading-none">{{ $c['value'] }}</p>
        <p class="text-xs font-medium text-surface-500 mt-1.5">{{ $c['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Member Card Banner --}}
<div class="relative overflow-hidden rounded-2xl p-5 mb-6 animate-fade-up" style="animation-delay:.1s;background:linear-gradient(135deg,#4361f0,#2a3ab8)">
    <div class="absolute -top-8 -right-8 w-32 h-32 bg-white/10 rounded-full"></div>
    <div class="absolute bottom-0 left-1/3 w-20 h-20 bg-white/5 rounded-full"></div>
    <div class="relative z-10 flex items-center justify-between">
        <div>
            <p class="text-white/60 text-xs font-semibold uppercase tracking-wider mb-1">Kartu Anggota Digital</p>
            <p class="text-white font-extrabold text-base">{{ auth()->user()->name }}</p>
            <p class="text-primary-200 text-sm font-mono mt-0.5">{{ auth()->user()->member_id }}</p>
        </div>
        <a href="{{ route('member.card') }}"
           class="flex items-center gap-2 bg-white/15 hover:bg-white/25 border border-white/20 text-white text-xs font-semibold px-4 py-2.5 rounded-xl transition-all">
            <i data-lucide="credit-card" class="w-4 h-4"></i> Lihat Kartu
        </a>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Active Loans --}}
    <div class="card overflow-hidden animate-fade-up" style="animation-delay:.15s">
        <div class="flex items-center justify-between px-5 py-4 border-b border-surface-50">
            <h3 class="text-sm font-bold text-surface-900">Sedang Dipinjam</h3>
            <a href="{{ route('loans.index') }}" class="text-xs text-primary-500 font-semibold hover:text-primary-700 transition-colors">Riwayat →</a>
        </div>
        <div class="divide-y divide-surface-50">
            @forelse($activeLoans as $loan)
            @php $over = $loan->due_date->lt(now()); @endphp
            <div class="flex items-start gap-3 px-5 py-4 hover:bg-surface-50/50 transition-colors">
                <div class="w-10 h-14 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:{{ $loan->book->category->color??'#4361f0' }}15">
                    <i data-lucide="book" class="w-5 h-5" style="color:{{ $loan->book->category->color??'#4361f0' }}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold text-surface-800 truncate">{{ $loan->book->title }}</p>
                    <p class="text-[11px] text-surface-400 mt-0.5">{{ $loan->book->author }}</p>
                    <div class="flex items-center gap-1.5 mt-2">
                        <i data-lucide="{{ $over?'alarm-clock':'clock' }}" class="w-3 h-3 {{ $over?'text-rose-500':'text-surface-400' }}"></i>
                        <span class="text-[11px] {{ $over?'text-rose-600 font-bold':'text-surface-400' }}">
                            {{ $over ? 'Terlambat '.$loan->due_date->diffInDays(now()).' hari' : 'Jatuh tempo '.$loan->due_date->format('d M Y') }}
                        </span>
                    </div>
                </div>
                <span class="badge {{ $over?'bg-rose-100 text-rose-600':'bg-sky-100 text-sky-600' }} flex-shrink-0">
                    {{ $over?'Terlambat':'Aktif' }}
                </span>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-12">
                <div class="w-12 h-12 rounded-2xl bg-surface-100 flex items-center justify-center mb-3">
                    <i data-lucide="book-open" class="w-6 h-6 text-surface-400"></i>
                </div>
                <p class="text-sm font-semibold text-surface-600">Tidak ada pinjaman aktif</p>
                <a href="{{ route('books.index') }}" class="text-xs text-primary-500 mt-1.5 hover:underline font-medium">Cari buku →</a>
            </div>
            @endforelse
        </div>
    </div>

    {{-- New Books --}}
    <div class="card overflow-hidden animate-fade-up" style="animation-delay:.2s">
        <div class="flex items-center justify-between px-5 py-4 border-b border-surface-50">
            <h3 class="text-sm font-bold text-surface-900">Buku Terbaru</h3>
            <a href="{{ route('books.index') }}" class="text-xs text-primary-500 font-semibold hover:text-primary-700 transition-colors">Lihat semua →</a>
        </div>
        <div class="divide-y divide-surface-50">
            @foreach($newBooks as $book)
            <a href="{{ route('books.show',$book) }}"
               class="flex items-center gap-3 px-5 py-3 hover:bg-surface-50/50 transition-colors block">
                <div class="w-9 h-11 rounded-lg flex items-center justify-center flex-shrink-0"
                     style="background:{{ $book->category->color??'#4361f0' }}15">
                    <i data-lucide="book" class="w-4 h-4" style="color:{{ $book->category->color??'#4361f0' }}"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-surface-800 truncate">{{ $book->title }}</p>
                    <p class="text-[10px] text-surface-400 mt-0.5">{{ $book->author }}</p>
                </div>
                <span class="badge flex-shrink-0 {{ $book->isAvailable()?'bg-emerald-100 text-emerald-600':'bg-surface-100 text-surface-500' }}">
                    {{ $book->isAvailable()?'Tersedia':'Dipinjam' }}
                </span>
            </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
