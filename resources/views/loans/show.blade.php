@extends('layouts.app')
@section('title', 'Detail Peminjaman #' . $loan->id)
@section('page-title', 'Detail Peminjaman')
@section('breadcrumb', 'Peminjaman → #' . $loan->id)

@section('content')
@php
$statusConfig = [
    'active'   => ['label'=>'Aktif',     'class'=>'bg-sky-100 text-sky-700',     'icon'=>'clock'],
    'overdue'  => ['label'=>'Terlambat', 'class'=>'bg-red-100 text-red-700',     'icon'=>'alert-triangle'],
    'returned' => ['label'=>'Kembali',   'class'=>'bg-emerald-100 text-emerald-700','icon'=>'check-circle-2'],
][$loan->status] ?? ['label'=>$loan->status,'class'=>'bg-slate-100 text-slate-600','icon'=>'info'];
@endphp

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-5">
        {{-- Status Banner --}}
        <div class="flex items-center gap-3 p-4 rounded-2xl border {{ $loan->status === 'overdue' ? 'bg-red-50 border-red-200' : ($loan->status === 'returned' ? 'bg-emerald-50 border-emerald-200' : 'bg-sky-50 border-sky-200') }}">
            <i data-lucide="{{ $statusConfig['icon'] }}" class="w-5 h-5 {{ $loan->status === 'overdue' ? 'text-red-500' : ($loan->status === 'returned' ? 'text-emerald-500' : 'text-sky-500') }}"></i>
            <div>
                <p class="font-semibold text-sm {{ $loan->status === 'overdue' ? 'text-red-800' : ($loan->status === 'returned' ? 'text-emerald-800' : 'text-sky-800') }}">
                    Status: {{ $statusConfig['label'] }}
                </p>
                @if($loan->status === 'overdue')
                <p class="text-xs text-red-600">Terlambat {{ $loan->due_date->diffInDays(now()) }} hari</p>
                @elseif($loan->status === 'returned' && $loan->return_date)
                <p class="text-xs text-emerald-600">Dikembalikan {{ $loan->return_date->format('d M Y') }}</p>
                @else
                <p class="text-xs text-sky-600">Jatuh tempo: {{ $loan->due_date->format('d M Y') }} ({{ $loan->due_date->diffForHumans() }})</p>
                @endif
            </div>
        </div>

        {{-- Loan Details --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-5">Informasi Peminjaman</h3>
            <div class="grid grid-cols-2 gap-5 text-sm">
                <div>
                    <p class="text-slate-400 mb-0.5">ID Peminjaman</p>
                    <p class="font-semibold text-slate-700">#{{ $loan->id }}</p>
                </div>
                <div>
                    <p class="text-slate-400 mb-0.5">Tanggal Pinjam</p>
                    <p class="font-semibold text-slate-700">{{ $loan->loan_date->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-slate-400 mb-0.5">Jatuh Tempo</p>
                    <p class="font-semibold {{ $loan->isOverdue() ? 'text-red-600' : 'text-slate-700' }}">{{ $loan->due_date->format('d M Y') }}</p>
                </div>
                @if($loan->return_date)
                <div>
                    <p class="text-slate-400 mb-0.5">Tanggal Kembali</p>
                    <p class="font-semibold text-slate-700">{{ $loan->return_date->format('d M Y') }}</p>
                </div>
                @endif
                @if($loan->processedBy)
                <div>
                    <p class="text-slate-400 mb-0.5">Diproses Oleh</p>
                    <p class="font-semibold text-slate-700">{{ $loan->processedBy->name }}</p>
                </div>
                @endif
                @if($loan->notes)
                <div class="col-span-2">
                    <p class="text-slate-400 mb-0.5">Catatan</p>
                    <p class="font-medium text-slate-700">{{ $loan->notes }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Book & Member --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-4">Data Buku</h3>
            <div class="flex items-start gap-4">
                <div class="w-14 h-18 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: {{ $loan->book->category->color ?? '#6366f1' }}20">
                    <i data-lucide="book" class="w-6 h-6" style="color: {{ $loan->book->category->color ?? '#6366f1' }}"></i>
                </div>
                <div>
                    <p class="font-bold text-slate-800">{{ $loan->book->title }}</p>
                    <p class="text-sm text-slate-500 mt-0.5">{{ $loan->book->author }}</p>
                    <p class="text-xs text-slate-400 mt-1">Rak: {{ $loan->book->shelf_location ?? '-' }} · Kategori: {{ $loan->book->category->name }}</p>
                    <a href="{{ route('books.show', $loan->book) }}" class="text-xs text-primary-600 mt-1 inline-block hover:underline">Lihat detail buku →</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar: Fine & Actions --}}
    <div class="space-y-4">
        {{-- Fine Card --}}
        @php
        $currentFine = $loan->calculateFine();
        @endphp
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Denda</h3>
            @if($currentFine > 0 || $loan->fine_amount > 0)
            <div class="text-center py-2">
                <p class="text-3xl font-bold {{ $loan->fine_paid ? 'text-slate-400' : 'text-red-600' }}">
                    Rp {{ number_format($loan->fine_amount > 0 ? $loan->fine_amount : $currentFine, 0, ',', '.') }}
                </p>
                <p class="text-xs text-slate-400 mt-1">
                    @if($loan->fine_paid)
                    <span class="text-emerald-600 font-medium">✓ Sudah dilunasi</span>
                    @else
                    Belum dibayar
                    @endif
                </p>
            </div>
            @if(auth()->user()->isAdmin() && !$loan->fine_paid && $loan->fine_amount > 0)
            <form method="POST" action="{{ route('loans.pay-fine', $loan) }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2.5 rounded-xl transition flex items-center gap-2 justify-center">
                    <i data-lucide="check" class="w-4 h-4"></i> Tandai Lunas
                </button>
            </form>
            @endif
            @else
            <div class="flex items-center gap-2 text-emerald-600 bg-emerald-50 p-3 rounded-xl">
                <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                <p class="text-sm">Tidak ada denda</p>
            </div>
            @endif
        </div>

        {{-- Admin Actions --}}
        @if(auth()->user()->isAdmin() && in_array($loan->status, ['active', 'overdue']))
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Tindakan</h3>
            <form method="POST" action="{{ route('loans.return', $loan) }}"
                  onsubmit="return confirm('Konfirmasi pengembalian buku ini?')">
                @csrf
                <button type="submit" class="w-full flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition justify-center">
                    <i data-lucide="undo-2" class="w-4 h-4"></i> Proses Pengembalian
                </button>
            </form>
        </div>
        @endif

        {{-- Member Info --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <h3 class="font-semibold text-slate-800 mb-4">Anggota</h3>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center font-bold text-primary-700">
                    {{ strtoupper(substr($loan->user->name, 0, 2)) }}
                </div>
                <div>
                    <p class="font-semibold text-slate-800">{{ $loan->user->name }}</p>
                    <p class="text-xs text-slate-400">{{ $loan->user->member_id }}</p>
                </div>
            </div>
            @if(auth()->user()->isAdmin())
            <a href="{{ route('members.show', $loan->user) }}" class="text-xs text-primary-600 mt-3 inline-block hover:underline">
                Lihat profil anggota →
            </a>
            @endif
        </div>
    </div>
</div>
@endsection
