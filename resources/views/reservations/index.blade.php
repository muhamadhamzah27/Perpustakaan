@extends('layouts.app')
@section('title','Reservasi Buku')
@section('page-title','Reservasi Buku')

@section('content')
<div class="card overflow-hidden">
    <div class="p-4 border-b border-surface-100">
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="status" class="form-select w-auto min-w-40">
                <option value="">Semua Status</option>
                <option value="waiting"   {{ request('status')==='waiting'  ?'selected':'' }}>Menunggu</option>
                <option value="ready"     {{ request('status')==='ready'    ?'selected':'' }}>Siap Diambil</option>
                <option value="fulfilled" {{ request('status')==='fulfilled'?'selected':'' }}>Terpenuhi</option>
                <option value="cancelled" {{ request('status')==='cancelled'?'selected':'' }}>Dibatalkan</option>
            </select>
            <button type="submit" class="btn-primary">Filter</button>
            @if(request('status'))
            <a href="{{ route('reservations.index') }}" class="btn-ghost">Reset</a>
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
                    <th>Tanggal Reservasi</th>
                    <th>Status</th>
                    <th>Batas Ambil</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($reservations as $res)
                @php
                $cfg=['waiting'=>['Menunggu','bg-amber-100 text-amber-700'],'ready'=>['Siap Diambil','bg-emerald-100 text-emerald-700'],'fulfilled'=>['Terpenuhi','bg-surface-100 text-surface-500'],'cancelled'=>['Dibatalkan','bg-rose-100 text-rose-600']][$res->status]??[$res->status,'bg-surface-100 text-surface-500'];
                @endphp
                <tr>
                    <td class="text-surface-400 text-[11px]">{{ $res->id }}</td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <p class="text-xs font-semibold text-surface-800">{{ $res->user->name }}</p>
                        <p class="text-[10px] text-surface-400">{{ $res->user->member_id }}</p>
                    </td>
                    @endif
                    <td>
                        <p class="text-xs font-semibold text-surface-800 max-w-52 truncate">{{ $res->book->title }}</p>
                        <p class="text-[10px] text-surface-400">{{ $res->book->author }}</p>
                    </td>
                    <td class="text-xs text-surface-600">{{ $res->created_at->format('d M Y') }}</td>
                    <td><span class="badge {{ $cfg[1] }}">{{ $cfg[0] }}</span></td>
                    <td class="text-xs text-surface-600">{{ $res->expiry_date?->format('d M Y') ?? '—' }}</td>
                    <td>
                        @if(in_array($res->status,['waiting','ready']))
                        <form method="POST" action="{{ route('reservations.cancel',$res) }}"
                              onsubmit="return confirm('Batalkan reservasi ini?')">
                            @csrf
                            <button type="submit" class="text-xs text-rose-500 hover:text-rose-700 font-semibold transition-colors">
                                Batalkan
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-14">
                        <div class="flex flex-col items-center text-surface-400">
                            <i data-lucide="bookmark" class="w-10 h-10 mb-2 opacity-30"></i>
                            <p class="text-sm font-medium">Tidak ada data reservasi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-surface-50">{{ $reservations->links() }}</div>
</div>
@endsection
