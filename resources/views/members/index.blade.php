@extends('layouts.app')
@section('title','Manajemen Anggota')
@section('page-title','Manajemen Anggota')

@section('content')
<div class="card overflow-hidden">
    <div class="p-4 border-b border-surface-100">
        <form method="GET" class="flex flex-wrap gap-3">
            <div class="flex-1 min-w-56 relative">
                <i data-lucide="search" class="w-4 h-4 text-surface-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="form-input pl-10" placeholder="Cari nama, email, ID anggota…">
            </div>
            <select name="status" class="form-select w-auto min-w-36">
                <option value="">Semua Status</option>
                <option value="active"   {{ request('status')==='active'   ?'selected':'' }}>Aktif</option>
                <option value="inactive" {{ request('status')==='inactive' ?'selected':'' }}>Nonaktif</option>
            </select>
            <button type="submit" class="btn-primary">Cari</button>
            @if(request()->hasAny(['search','status']))
            <a href="{{ route('members.index') }}" class="btn-ghost">Reset</a>
            @endif
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full data-table">
            <thead>
                <tr>
                    <th>Anggota</th>
                    <th>ID Kartu</th>
                    <th>Telepon</th>
                    <th>Status</th>
                    <th>Bergabung</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center text-xs font-extrabold text-primary-700 flex-shrink-0">
                                {{ strtoupper(substr($member->name,0,2)) }}
                            </div>
                            <div>
                                <p class="text-xs font-bold text-surface-800">{{ $member->name }}</p>
                                <p class="text-[10px] text-surface-400">{{ $member->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="font-mono text-[11px] bg-surface-100 text-surface-600 px-2.5 py-1 rounded-lg font-semibold">
                            {{ $member->member_id }}
                        </span>
                    </td>
                    <td class="text-xs text-surface-600">{{ $member->phone ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $member->status==='active'?'bg-emerald-100 text-emerald-700':'bg-rose-100 text-rose-600' }}">
                            {{ $member->status==='active'?'Aktif':'Nonaktif' }}
                        </span>
                    </td>
                    <td class="text-xs text-surface-500">{{ $member->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('members.show',$member) }}"
                               class="text-xs text-primary-500 hover:text-primary-700 font-semibold transition-colors">Detail</a>
                            <span class="text-surface-200">|</span>
                            <form method="POST" action="{{ route('members.toggle-status',$member) }}">
                                @csrf
                                <button type="submit"
                                        class="text-xs {{ $member->status==='active'?'text-rose-500 hover:text-rose-700':'text-emerald-600 hover:text-emerald-800' }} font-semibold transition-colors">
                                    {{ $member->status==='active'?'Nonaktifkan':'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-14">
                        <div class="flex flex-col items-center text-surface-400">
                            <i data-lucide="users" class="w-10 h-10 mb-2 opacity-30"></i>
                            <p class="text-sm font-medium">Tidak ada data anggota</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-surface-50">{{ $members->links() }}</div>
</div>
@endsection
