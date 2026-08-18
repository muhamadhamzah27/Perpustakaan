@extends('layouts.app')
@section('title', 'Profil Anggota')
@section('page-title', 'Profil Anggota')
@section('breadcrumb', 'Anggota → ' . $member->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Profile Card --}}
    <div class="space-y-4">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center">
            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center text-xl font-bold text-primary-700 mx-auto mb-3">
                {{ strtoupper(substr($member->name, 0, 2)) }}
            </div>
            <h2 class="font-bold text-slate-800 text-lg">{{ $member->name }}</h2>
            <p class="text-slate-400 text-sm">{{ $member->email }}</p>
            <span class="inline-block mt-2 px-3 py-0.5 rounded-full text-xs font-medium {{ $member->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                {{ $member->status === 'active' ? 'Aktif' : 'Nonaktif' }}
            </span>

            <div class="mt-4 pt-4 border-t border-slate-50 text-left space-y-3 text-sm">
                <div class="flex items-center gap-2 text-slate-600">
                    <i data-lucide="credit-card" class="w-4 h-4 text-slate-400"></i>
                    <span class="font-mono text-xs bg-slate-100 px-2 py-0.5 rounded">{{ $member->member_id }}</span>
                </div>
                @if($member->phone)
                <div class="flex items-center gap-2 text-slate-600">
                    <i data-lucide="phone" class="w-4 h-4 text-slate-400"></i>
                    {{ $member->phone }}
                </div>
                @endif
                @if($member->address)
                <div class="flex items-start gap-2 text-slate-600">
                    <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 flex-shrink-0 mt-0.5"></i>
                    <span>{{ $member->address }}</span>
                </div>
                @endif
                <div class="flex items-center gap-2 text-slate-500">
                    <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                    Bergabung {{ $member->created_at->format('d M Y') }}
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5">
            <h3 class="font-semibold text-slate-800 mb-3">Statistik</h3>
            <div class="space-y-2 text-sm">
                @php
                $totalLoans    = $member->loans->count();
                $activeLoans   = $member->loans->whereIn('status', ['active','overdue'])->count();
                $returnedLoans = $member->loans->where('status','returned')->count();
                $totalFines    = $member->loans->where('fine_paid', false)->where('fine_amount', '>', 0)->sum('fine_amount');
                @endphp
                <div class="flex justify-between"><span class="text-slate-500">Total Pinjaman</span><span class="font-semibold text-slate-700">{{ $totalLoans }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Aktif</span><span class="font-semibold text-slate-700">{{ $activeLoans }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Dikembalikan</span><span class="font-semibold text-slate-700">{{ $returnedLoans }}</span></div>
                <div class="flex justify-between"><span class="text-slate-500">Denda Belum Lunas</span>
                    <span class="font-semibold {{ $totalFines > 0 ? 'text-red-600' : 'text-slate-700' }}">
                        Rp {{ number_format($totalFines, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('members.toggle-status', $member) }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2 justify-center text-sm font-medium px-4 py-2.5 rounded-xl border transition
                {{ $member->status === 'active' ? 'border-red-200 text-red-600 hover:bg-red-50' : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50' }}">
                <i data-lucide="{{ $member->status === 'active' ? 'user-x' : 'user-check' }}" class="w-4 h-4"></i>
                {{ $member->status === 'active' ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}
            </button>
        </form>
    </div>

    {{-- Loan History --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-50">
                <h3 class="font-semibold text-slate-800">Riwayat Peminjaman</h3>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($member->loans->sortByDesc('created_at')->take(10) as $loan)
                @php
                $cfg = ['active'=>['Aktif','bg-sky-100 text-sky-700'],'overdue'=>['Terlambat','bg-red-100 text-red-700'],'returned'=>['Kembali','bg-emerald-100 text-emerald-700']][$loan->status] ?? [$loan->status,'bg-slate-100 text-slate-600'];
                @endphp
                <div class="flex items-center gap-3 px-5 py-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-700 truncate">{{ $loan->book->title }}</p>
                        <p class="text-xs text-slate-400">{{ $loan->loan_date->format('d M Y') }} → {{ $loan->due_date->format('d M Y') }}</p>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $cfg[1] }}">{{ $cfg[0] }}</span>
                    @if($loan->fine_amount > 0)
                    <span class="text-xs font-medium text-red-600">Rp {{ number_format($loan->fine_amount,0,',','.') }}</span>
                    @endif
                    <a href="{{ route('loans.show', $loan) }}" class="text-xs text-primary-600 hover:underline">Detail</a>
                </div>
                @empty
                <div class="text-center py-8 text-slate-400">
                    <i data-lucide="book" class="w-8 h-8 mx-auto mb-2 opacity-30"></i>
                    <p class="text-sm">Belum ada riwayat peminjaman</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
