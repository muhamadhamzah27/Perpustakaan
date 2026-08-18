@extends('layouts.app')
@section('title','Dashboard Admin')
@section('page-title','Dashboard')
@section('breadcrumb','Selamat datang kembali, ' . auth()->user()->name)

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
@endpush

@section('content')

{{-- ── Stat Cards ── --}}
<div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    @php
    $cards = [
        ['label'=>'Total Judul',   'value'=>$totalTitles,      'sub'=>$totalBooks.' eksemplar',   'icon'=>'library',         'from'=>'from-primary-500', 'to'=>'to-primary-600', 'light'=>'bg-primary-50',  'text'=>'text-primary-600'],
        ['label'=>'Anggota Aktif', 'value'=>$totalMembers,     'sub'=>'terdaftar',                'icon'=>'users-round',     'from'=>'from-sky-500',     'to'=>'to-sky-600',     'light'=>'bg-sky-50',      'text'=>'text-sky-600'],
        ['label'=>'Dipinjam',      'value'=>$totalActiveLoans, 'sub'=>'transaksi aktif',          'icon'=>'book-copy',       'from'=>'from-violet-500',  'to'=>'to-violet-600',  'light'=>'bg-violet-50',   'text'=>'text-violet-600'],
        ['label'=>'Terlambat',     'value'=>$totalOverdue,     'sub'=>'butuh perhatian',          'icon'=>'alarm-clock',     'from'=>'from-rose-500',    'to'=>'to-rose-600',    'light'=>'bg-rose-50',     'text'=>'text-rose-600'],
    ];
    @endphp

    @foreach($cards as $i => $c)
    <div class="card p-5 animate-fade-up hover:shadow-card-md transition-shadow duration-200" style="animation-delay:{{ $i * .06 }}s">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-2xl {{ $c['light'] }} {{ $c['text'] }} flex items-center justify-center">
                <i data-lucide="{{ $c['icon'] }}" class="w-5 h-5" stroke-width="2"></i>
            </div>
            @if($c['label']==='Terlambat' && $totalOverdue > 0)
            <span class="badge bg-rose-100 text-rose-600">!</span>
            @endif
        </div>
        <p class="text-2xl font-extrabold text-surface-900 leading-none">{{ $c['value'] }}</p>
        <p class="text-xs font-semibold text-surface-500 mt-1.5">{{ $c['label'] }}</p>
        <p class="text-[11px] text-surface-400 mt-0.5">{{ $c['sub'] }}</p>
    </div>
    @endforeach
</div>

{{-- ── Charts Row ── --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-5">

    {{-- Loan Bar Chart --}}
    <div class="lg:col-span-2 card p-5 animate-fade-up" style="animation-delay:.1s">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="text-sm font-bold text-surface-900">Tren Peminjaman</h3>
                <p class="text-xs text-surface-400 mt-0.5">6 bulan terakhir</p>
            </div>
            <span class="badge bg-primary-50 text-primary-600">Bulanan</span>
        </div>
        <div style="height:200px">
            <canvas id="loanChart"></canvas>
        </div>
    </div>

    {{-- Top Books --}}
    <div class="card p-5 animate-fade-up" style="animation-delay:.15s">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-surface-900">Buku Terpopuler</h3>
            <i data-lucide="trophy" class="w-4 h-4 text-amber-400"></i>
        </div>
        <div class="space-y-3">
            @forelse($topBooks->take(5) as $i => $book)
            <div class="flex items-center gap-3">
                <div class="w-6 h-6 rounded-lg flex items-center justify-center text-[10px] font-extrabold flex-shrink-0
                    {{ $i===0 ? 'bg-amber-100 text-amber-600' : ($i===1 ? 'bg-surface-100 text-surface-500' : 'bg-surface-50 text-surface-400') }}">
                    {{ $i+1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-surface-700 truncate">{{ $book->title }}</p>
                    <div class="mt-1 bg-surface-100 rounded-full h-1 overflow-hidden">
                        @php $maxCount = $topBooks->max('loans_count') ?: 1; @endphp
                        <div class="h-full bg-primary-400 rounded-full transition-all duration-500"
                             style="width:{{ ($book->loans_count/$maxCount)*100 }}%"></div>
                    </div>
                </div>
                <span class="text-[10px] font-bold text-surface-400 flex-shrink-0">{{ $book->loans_count }}×</span>
            </div>
            @empty
            <p class="text-xs text-surface-400 text-center py-4">Belum ada data</p>
            @endforelse
        </div>
    </div>
</div>

{{-- ── Bottom Row ── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    {{-- Recent Loans --}}
    <div class="card overflow-hidden animate-fade-up" style="animation-delay:.2s">
        <div class="flex items-center justify-between px-5 py-4 border-b border-surface-50">
            <h3 class="text-sm font-bold text-surface-900">Peminjaman Terbaru</h3>
            <a href="{{ route('loans.index') }}" class="text-xs text-primary-500 hover:text-primary-600 font-semibold transition-colors">
                Lihat semua →
            </a>
        </div>
        <div class="divide-y divide-surface-50">
            @forelse($recentLoans as $loan)
            @php
            $s=['active'=>['Aktif','bg-sky-100 text-sky-600'],'returned'=>['Kembali','bg-emerald-100 text-emerald-600'],'overdue'=>['Terlambat','bg-rose-100 text-rose-600']][$loan->status]??[$loan->status,'bg-surface-100 text-surface-500'];
            @endphp
            <div class="flex items-center gap-3 px-5 py-3 hover:bg-surface-50/50 transition-colors">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-surface-200 to-surface-300 flex items-center justify-center text-[10px] font-bold text-surface-600 flex-shrink-0">
                    {{ strtoupper(substr($loan->user->name,0,2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-surface-800 truncate">{{ $loan->book->title }}</p>
                    <p class="text-[11px] text-surface-400 mt-0.5">{{ $loan->user->name }} · {{ $loan->loan_date->format('d M') }}</p>
                </div>
                <span class="badge {{ $s[1] }}">{{ $s[0] }}</span>
            </div>
            @empty
            <p class="text-xs text-surface-400 text-center py-8">Belum ada peminjaman</p>
            @endforelse
        </div>
    </div>

    {{-- Overdue --}}
    <div class="card overflow-hidden animate-fade-up" style="animation-delay:.25s">
        <div class="flex items-center justify-between px-5 py-4 border-b border-surface-50">
            <div class="flex items-center gap-2">
                <h3 class="text-sm font-bold text-surface-900">Terlambat Dikembalikan</h3>
                @if($totalOverdue > 0)
                <span class="badge bg-rose-500 text-white">{{ $totalOverdue }}</span>
                @endif
            </div>
            <a href="{{ route('loans.index',['status'=>'overdue']) }}" class="text-xs text-primary-500 hover:text-primary-600 font-semibold transition-colors">
                Lihat semua →
            </a>
        </div>
        <div class="divide-y divide-surface-50">
            @forelse($overdueLoans as $loan)
            @php $days = $loan->due_date->diffInDays(now()); @endphp
            <div class="flex items-center gap-3 px-5 py-3 hover:bg-surface-50/50 transition-colors">
                <div class="w-8 h-8 rounded-xl bg-rose-100 flex items-center justify-center flex-shrink-0">
                    <i data-lucide="clock-alert" class="w-4 h-4 text-rose-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold text-surface-800 truncate">{{ $loan->book->title }}</p>
                    <p class="text-[11px] text-surface-400 mt-0.5">{{ $loan->user->name }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    <p class="text-xs font-extrabold text-rose-500">+{{ $days }}h</p>
                    <p class="text-[10px] text-surface-400">hari</p>
                </div>
            </div>
            @empty
            <div class="flex flex-col items-center justify-center py-10">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center mb-3">
                    <i data-lucide="check-circle-2" class="w-6 h-6 text-emerald-500"></i>
                </div>
                <p class="text-xs font-semibold text-surface-600">Semua tepat waktu!</p>
                <p class="text-[11px] text-surface-400 mt-0.5">Tidak ada keterlambatan</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const ctx = document.getElementById('loanChart');
const labels = @json($monthlyLabels);
const data   = @json($monthlyLoans);

new Chart(ctx, {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Peminjaman',
            data,
            backgroundColor: (ctx) => {
                const g = ctx.chart.ctx.createLinearGradient(0,0,0,200);
                g.addColorStop(0,'rgba(67,97,240,.8)');
                g.addColorStop(1,'rgba(67,97,240,.1)');
                return g;
            },
            borderColor: '#4361f0',
            borderWidth: 0,
            borderRadius: 8,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#0f172a', titleColor: '#94a3b8', bodyColor: '#f8fafc',
                padding: 10, cornerRadius: 10, titleFont: { size: 11 }, bodyFont: { size: 13, weight: 'bold' },
                callbacks: { title: i => i[0].label, label: i => ` ${i.raw} peminjaman` }
            }
        },
        scales: {
            x: { grid: { display: false }, border: { display: false }, ticks: { color: '#94a3b8', font: { size: 11 } } },
            y: { beginAtZero: true, border: { display: false }, grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', font: { size: 11 }, stepSize: 1 } }
        }
    }
});
</script>
@endpush
