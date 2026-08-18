@extends('layouts.app')
@section('title','Laporan')
@section('page-title','Laporan Perpustakaan')
@section('breadcrumb','Analitik & Statistik Bulanan')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@endpush

@section('content')

{{-- Filter --}}
<div class="card p-4 mb-6">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">Bulan</label>
            <select name="month" class="form-select w-auto min-w-36">
                @foreach($months as $num => $name)
                <option value="{{ $num }}" {{ $month==$num?'selected':'' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">Tahun</label>
            <select name="year" class="form-select w-auto min-w-24">
                @foreach($years as $y)
                <option value="{{ $y }}" {{ $year==$y?'selected':'' }}>{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn-primary">
            <i data-lucide="filter" class="w-3.5 h-3.5"></i> Tampilkan
        </button>
    </form>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
    $rc=[
        ['Total Pinjaman',$totalLoans,   'arrow-left-right','bg-primary-50','text-primary-600'],
        ['Dikembalikan',  $returned,     'check-circle-2',  'bg-emerald-50','text-emerald-600'],
        ['Terlambat',     $overdue,      'alarm-clock',     'bg-rose-50',   'text-rose-600'],
        ['Anggota Aktif', $activeMembers,'users-round',     'bg-sky-50',    'text-sky-600'],
    ];
    @endphp
    @foreach($rc as $i=>[$label,$val,$icon,$bg,$tc])
    <div class="card p-5 animate-fade-up" style="animation-delay:{{ $i*.06 }}s">
        <div class="w-10 h-10 {{ $bg }} {{ $tc }} rounded-2xl flex items-center justify-center mb-4">
            <i data-lucide="{{ $icon }}" class="w-5 h-5"></i>
        </div>
        <p class="text-2xl font-extrabold text-surface-900 leading-none">{{ $val }}</p>
        <p class="text-xs font-semibold text-surface-500 mt-1.5">{{ $label }}</p>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">
    {{-- Fine Summary --}}
    <div class="card p-6 animate-fade-up" style="animation-delay:.1s">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-xl bg-amber-50 flex items-center justify-center">
                <i data-lucide="banknote" class="w-4 h-4 text-amber-600"></i>
            </div>
            <h3 class="font-bold text-surface-900 text-sm">Ringkasan Denda</h3>
        </div>
        <div class="space-y-3">
            @foreach([
                ['Total Denda','Rp '.number_format($totalFines,0,',','.'),'text-surface-800'],
                ['Sudah Dibayar','Rp '.number_format($paidFines,0,',','.'),'text-emerald-600'],
                ['Belum Dibayar','Rp '.number_format($totalFines-$paidFines,0,',','.'),'text-rose-600'],
            ] as [$l,$v,$tc])
            <div class="flex justify-between items-center py-2.5 border-b border-surface-50 last:border-0">
                <span class="text-xs text-surface-500 font-medium">{{ $l }}</span>
                <span class="text-sm font-extrabold {{ $tc }}">{{ $v }}</span>
            </div>
            @endforeach
        </div>
        @if($totalLoans > 0)
        <div class="mt-4 bg-surface-50 rounded-2xl p-4">
            @php $rate = round(($returned/max($totalLoans,1))*100); @endphp
            <div class="flex justify-between items-center mb-2">
                <p class="text-xs text-surface-500 font-medium">Tingkat Ketepatan</p>
                <p class="text-xs font-extrabold text-surface-800">{{ $rate }}%</p>
            </div>
            <div class="bg-surface-200 rounded-full h-2 overflow-hidden">
                <div class="h-full rounded-full transition-all duration-700"
                     style="width:{{ $rate }}%;background:linear-gradient(90deg,#4361f0,#6388fb)"></div>
            </div>
        </div>
        @endif
    </div>

    {{-- Top Books Horizontal Chart --}}
    <div class="card p-6 animate-fade-up" style="animation-delay:.15s">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-xl bg-primary-50 flex items-center justify-center">
                <i data-lucide="bar-chart-3" class="w-4 h-4 text-primary-600"></i>
            </div>
            <h3 class="font-bold text-surface-900 text-sm">Buku Terpopuler Bulan Ini</h3>
        </div>
        @if($topBooks->where('loans_count','>',0)->count())
        <div style="height:190px">
            <canvas id="topBooksChart"></canvas>
        </div>
        @else
        <div class="flex flex-col items-center justify-center py-10 text-surface-400">
            <i data-lucide="bar-chart-2" class="w-10 h-10 mb-2 opacity-30"></i>
            <p class="text-sm font-medium">Tidak ada peminjaman bulan ini</p>
        </div>
        @endif
    </div>
</div>

{{-- Loans Table --}}
<div class="card overflow-hidden animate-fade-up" style="animation-delay:.2s">
    <div class="flex items-center justify-between px-5 py-4 border-b border-surface-50">
        <h3 class="text-sm font-bold text-surface-900">
            Detail Peminjaman — {{ $months[$month] }} {{ $year }}
        </h3>
        <span class="badge bg-primary-50 text-primary-600">{{ $loans->count() }} transaksi</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full data-table">
            <thead>
                <tr>
                    @foreach(['#','Anggota','Buku','Tgl Pinjam','Jatuh Tempo','Kembali','Status','Denda'] as $h)
                    <th>{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse($loans as $loan)
                @php
                $sc=['active'=>['Aktif','bg-sky-100 text-sky-600'],'overdue'=>['Terlambat','bg-rose-100 text-rose-600'],'returned'=>['Kembali','bg-emerald-100 text-emerald-600']][$loan->status]??[$loan->status,'bg-surface-100 text-surface-500'];
                @endphp
                <tr>
                    <td class="text-surface-400 text-[11px]">{{ $loan->id }}</td>
                    <td>
                        <p class="text-xs font-semibold text-surface-800">{{ $loan->user->name }}</p>
                        <p class="text-[10px] text-surface-400">{{ $loan->user->member_id }}</p>
                    </td>
                    <td class="text-xs font-medium text-surface-700 max-w-40 truncate">{{ $loan->book->title }}</td>
                    <td class="text-xs text-surface-600">{{ $loan->loan_date->format('d M Y') }}</td>
                    <td class="text-xs text-surface-600">{{ $loan->due_date->format('d M Y') }}</td>
                    <td class="text-xs text-surface-600">{{ $loan->return_date?->format('d M Y') ?? '—' }}</td>
                    <td><span class="badge {{ $sc[1] }}">{{ $sc[0] }}</span></td>
                    <td class="text-xs {{ $loan->fine_amount>0?'text-rose-600 font-bold':'text-surface-400' }}">
                        {{ $loan->fine_amount>0 ? 'Rp '.number_format($loan->fine_amount,0,',','.') : '—' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-10 text-surface-400 text-sm">Tidak ada data untuk periode ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
@if($topBooks->where('loans_count','>',0)->count())
<script>
const booksData = @json($topBooks->where('loans_count','>',0)->values());
new Chart(document.getElementById('topBooksChart'), {
    type: 'bar',
    data: {
        labels: booksData.map(b => b.title.length > 22 ? b.title.substring(0,22)+'…' : b.title),
        datasets: [{ data: booksData.map(b=>b.loans_count), backgroundColor: '#4361f0', borderRadius: 6, borderSkipped: false }]
    },
    options: {
        responsive:true, maintainAspectRatio:false, indexAxis:'y',
        plugins: { legend:{display:false}, tooltip:{backgroundColor:'#0f172a',titleColor:'#94a3b8',bodyColor:'#f8fafc',padding:10,cornerRadius:10} },
        scales: {
            x: { beginAtZero:true, border:{display:false}, grid:{color:'#f1f5f9'}, ticks:{color:'#94a3b8',font:{size:11},stepSize:1} },
            y: { grid:{display:false}, ticks:{color:'#475569',font:{size:11}} }
        }
    }
});
</script>
@endif
@endpush
