@extends('layouts.app')
@section('title','Peminjaman')
@section('page-title', auth()->user()->isAdmin() ? 'Manajemen Peminjaman' : 'Riwayat Peminjaman')

@section('header-actions')
@if(auth()->user()->isAdmin())
<a href="{{ route('loans.create') }}" class="btn-primary">
    <i data-lucide="plus" class="w-3.5 h-3.5"></i> Catat Peminjaman
</a>
@endif
@endsection

@section('content')
<div class="card overflow-hidden">
    {{-- Filter --}}
    <div class="p-4 border-b border-surface-100">
        <form method="GET" class="flex flex-wrap gap-3">
            @if(auth()->user()->isAdmin())
            <div class="flex-1 min-w-48 relative">
                <i data-lucide="search" class="w-4 h-4 text-surface-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-input pl-10" placeholder="Cari anggota atau judul buku…">
            </div>
            @endif
            <select name="status" class="form-select w-auto min-w-36">
                <option value="">Semua Status</option>
                <option value="active"   {{ request('status')==='active'?'selected':'' }}>Aktif</option>
                <option value="overdue"  {{ request('status')==='overdue'?'selected':'' }}>Terlambat</option>
                <option value="returned" {{ request('status')==='returned'?'selected':'' }}>Dikembalikan</option>
            </select>
            <button type="submit" class="btn-primary">Filter</button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('loans.index') }}" class="btn-ghost">Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full data-table">
            <thead>
                <tr>
                    <th>#</th>
                    @if(auth()->user()->isAdmin())<th>Anggota</th>@endif
                    <th>Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Denda</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                @php
                $sc=['active'=>['Aktif','bg-sky-100 text-sky-600'],'overdue'=>['Terlambat','bg-rose-100 text-rose-600'],'returned'=>['Kembali','bg-emerald-100 text-emerald-600']][$loan->status]??[$loan->status,'bg-surface-100 text-surface-500'];
                @endphp
                <tr>
                    <td class="text-surface-400 text-[11px]">{{ $loan->id }}</td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center text-[10px] font-bold text-primary-700 flex-shrink-0">
                                {{ strtoupper(substr($loan->user->name,0,2)) }}
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-surface-800">{{ $loan->user->name }}</p>
                                <p class="text-[10px] text-surface-400">{{ $loan->user->member_id }}</p>
                            </div>
                        </div>
                    </td>
                    @endif
                    <td>
                        <p class="text-xs font-semibold text-surface-800 max-w-48 truncate">{{ $loan->book->title }}</p>
                        <p class="text-[10px] text-surface-400 truncate">{{ $loan->book->author }}</p>
                    </td>
                    <td class="text-xs text-surface-600">{{ $loan->loan_date->format('d M Y') }}</td>
                    <td class="text-xs {{ $loan->isOverdue() ? 'text-rose-600 font-bold' : 'text-surface-600' }}">
                        {{ $loan->due_date->format('d M Y') }}
                    </td>
                    <td><span class="badge {{ $sc[1] }}">{{ $sc[0] }}</span></td>
                    <td>
                        @if($loan->fine_amount > 0)
                        <p class="text-xs font-bold {{ $loan->fine_paid?'text-surface-400 line-through':'text-rose-600' }}">
                            Rp {{ number_format($loan->fine_amount,0,',','.') }}
                        </p>
                        @if($loan->fine_paid)<span class="text-[10px] text-emerald-600 font-semibold">Lunas</span>@endif
                        @else
                        <span class="text-surface-300 text-xs">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('loans.show',$loan) }}" class="text-xs text-primary-500 hover:text-primary-700 font-semibold transition-colors">
                            Detail →
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-14">
                        <div class="flex flex-col items-center text-surface-400">
                            <i data-lucide="inbox" class="w-10 h-10 mb-2 opacity-30"></i>
                            <p class="text-sm font-medium">Tidak ada data peminjaman</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-surface-50">{{ $loans->links() }}</div>
</div>
@endsection
