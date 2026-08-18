@extends('layouts.app')
@section('title','Kartu Anggota Digital')
@section('page-title','Kartu Anggota Digital')

@section('content')
<div class="max-w-md mx-auto space-y-5">

    {{-- Card --}}
    <div class="animate-scale-in">
        <div class="relative overflow-hidden rounded-3xl p-7 select-none"
             style="background:linear-gradient(135deg,#272f77 0%,#3248d9 45%,#4361f0 100%)">

            {{-- Decorative shapes --}}
            <div class="absolute -top-16 -right-16 w-52 h-52 rounded-full bg-white/10"></div>
            <div class="absolute -bottom-10 -left-10 w-40 h-40 rounded-full bg-white/8"></div>
            <div class="absolute top-6 right-28 w-10 h-10 rounded-full bg-white/8"></div>

            {{-- Shimmer stripe --}}
            <div class="absolute inset-0 opacity-10"
                 style="background:repeating-linear-gradient(45deg,transparent,transparent 10px,rgba(255,255,255,.05) 10px,rgba(255,255,255,.05) 20px)"></div>

            <div class="relative z-10">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center">
                            <i data-lucide="book-open" class="w-4 h-4 text-white" stroke-width="2.5"></i>
                        </div>
                        <div>
                            <p class="text-white font-extrabold text-sm leading-none">Perpustakaan</p>
                            <p class="text-white/50 text-[10px] mt-0.5">Digital Library</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 bg-white/15 rounded-full px-2.5 py-1">
                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></div>
                        <span class="text-white/80 text-[10px] font-semibold uppercase tracking-wider">MEMBER</span>
                    </div>
                </div>

                {{-- QR + Name --}}
                <div class="flex items-end gap-5 mb-7">
                    <div class="bg-white rounded-2xl p-2.5 shadow-xl shadow-black/20 flex-shrink-0">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=96x96&data={{ urlencode($user->member_id) }}&bgcolor=ffffff&color=272f77&margin=0&qzone=1"
                             alt="QR Code" class="w-24 h-24 rounded-lg">
                    </div>
                    <div class="pb-1">
                        <p class="text-white/50 text-[10px] font-semibold uppercase tracking-widest mb-1">Nama Anggota</p>
                        <p class="text-white font-extrabold text-xl leading-tight">{{ $user->name }}</p>
                        <p class="text-white/60 text-xs mt-1">{{ $user->email }}</p>
                    </div>
                </div>

                {{-- Card number --}}
                <div class="mb-6">
                    <p class="text-white/40 text-[9px] font-bold uppercase tracking-[.15em] mb-1.5">Nomor Anggota</p>
                    <p class="text-white font-mono font-extrabold text-2xl tracking-[.25em] drop-shadow">
                        {{ $user->member_id }}
                    </p>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-between pt-4 border-t border-white/15">
                    <div>
                        <p class="text-white/40 text-[9px] uppercase tracking-widest">Terdaftar</p>
                        <p class="text-white/80 text-xs font-semibold mt-0.5">{{ $user->created_at->format('M Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-white/40 text-[9px] uppercase tracking-widest">Status</p>
                        <div class="flex items-center justify-end gap-1.5 mt-0.5">
                            <div class="w-1.5 h-1.5 rounded-full {{ $user->status==='active'?'bg-emerald-400':'bg-rose-400' }}"></div>
                            <p class="text-white/80 text-xs font-semibold capitalize">{{ $user->status==='active'?'Aktif':'Nonaktif' }}</p>
                        </div>
                    </div>
                    {{-- Chip decoration --}}
                    <div class="w-10 h-8 rounded-md border border-amber-300/50 bg-amber-200/20 grid grid-cols-2 gap-px p-1">
                        @for($r=0;$r<4;$r++)<div class="bg-amber-300/40 rounded-[1px]"></div>@endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Stats --}}
    @php
    $activeL = \App\Models\Loan::where('user_id',$user->id)->whereIn('status',['active','overdue'])->count();
    $totalL  = \App\Models\Loan::where('user_id',$user->id)->count();
    $resC    = \App\Models\Reservation::where('user_id',$user->id)->whereIn('status',['waiting','ready'])->count();
    @endphp
    <div class="grid grid-cols-3 gap-3 animate-fade-up" style="animation-delay:.1s">
        @foreach([[$activeL,'Dipinjam','book-open','text-primary-600','bg-primary-50'],[$totalL,'Total Pinjam','history','text-sky-600','bg-sky-50'],[$resC,'Reservasi','bookmark','text-amber-600','bg-amber-50']] as [$v,$l,$ic,$tc,$bg])
        <div class="card p-4 text-center">
            <div class="w-8 h-8 {{ $bg }} {{ $tc }} rounded-xl flex items-center justify-center mx-auto mb-2">
                <i data-lucide="{{ $ic }}" class="w-4 h-4"></i>
            </div>
            <p class="text-xl font-extrabold text-surface-900">{{ $v }}</p>
            <p class="text-[10px] text-surface-400 mt-0.5 font-medium">{{ $l }}</p>
        </div>
        @endforeach
    </div>

    {{-- Usage Guide --}}
    <div class="card p-5 animate-fade-up" style="animation-delay:.15s">
        <h3 class="text-sm font-bold text-surface-900 mb-4">Cara Menggunakan</h3>
        <div class="space-y-3">
            @foreach([
                ['qr-code','Tunjukkan QR code di atas kepada petugas untuk verifikasi identitas'],
                ['credit-card','Nomor anggota digunakan saat proses peminjaman dan pengembalian buku'],
                ['smartphone','Screenshot atau print halaman ini sebagai bukti keanggotaan'],
            ] as [$ic,$text])
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i data-lucide="{{ $ic }}" class="w-4 h-4 text-primary-600"></i>
                </div>
                <p class="text-xs text-surface-600 leading-relaxed pt-1.5">{{ $text }}</p>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
