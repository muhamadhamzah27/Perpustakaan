@extends('layouts.guest')
@section('title','Masuk — Perpustakaan Digital')

@section('content')
<div class="min-h-screen flex">

    {{-- ── Left branding panel ── --}}
    <div class="hidden lg:flex lg:w-[52%] flex-col justify-between p-14 relative overflow-hidden">
        <div class="flex items-center gap-3 animate-fade-in">
            <div class="w-10 h-10 rounded-2xl bg-primary-500 flex items-center justify-center shadow-lg shadow-primary-500/40">
                <i data-lucide="book-open" class="w-5 h-5 text-white" stroke-width="2.5"></i>
            </div>
            <span class="text-white font-bold text-[15px] tracking-tight">Perpustakaan Digital</span>
        </div>

        <div class="animate-fade-up" style="animation-delay:.1s">
            <div class="inline-flex items-center gap-2 bg-white/10 border border-white/15 rounded-full px-3.5 py-1.5 mb-6">
                <div class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></div>
                <span class="text-white/70 text-xs font-medium">Sistem Aktif</span>
            </div>

            <h1 class="text-5xl font-extrabold text-white leading-[1.1] tracking-tight mb-5">
                Kelola<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-300 to-indigo-300">
                    Koleksi Buku
                </span><br>
                dengan Mudah
            </h1>
            <p class="text-white/50 text-base leading-relaxed max-w-sm">
                Platform manajemen perpustakaan modern — peminjaman, reservasi, laporan, dan kartu anggota digital dalam satu sistem.
            </p>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-3 mt-10">
                @foreach([['16+','Koleksi Buku'],['5','Anggota Aktif'],['3','Kategori Utama']] as [$n,$l])
                <div class="glass rounded-2xl p-4">
                    <p class="text-white font-extrabold text-2xl leading-none">{{ $n }}</p>
                    <p class="text-white/40 text-xs mt-1.5 font-medium">{{ $l }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <p class="text-white/20 text-xs animate-fade-in">© {{ date('Y') }} Perpustakaan Digital. All rights reserved.</p>
    </div>

    {{-- ── Right form panel ── --}}
    <div class="w-full lg:w-[48%] flex items-center justify-center p-6">
        <div class="w-full max-w-[400px]">

            {{-- Mobile logo --}}
            <div class="flex items-center gap-2 mb-8 lg:hidden">
                <div class="w-8 h-8 rounded-xl bg-primary-500 flex items-center justify-center">
                    <i data-lucide="book-open" class="w-4 h-4 text-white"></i>
                </div>
                <span class="text-white font-bold text-sm">Perpustakaan Digital</span>
            </div>

            <div class="glass rounded-3xl p-8 shadow-2xl animate-fade-up">
                <div class="mb-8">
                    <h2 class="text-2xl font-extrabold text-white tracking-tight">Selamat Datang 👋</h2>
                    <p class="text-white/40 text-sm mt-1.5">Masuk untuk mengakses perpustakaan digital</p>
                </div>

                <form method="POST" action="{{ route('login') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-white/60 uppercase tracking-wider mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="input-dark" placeholder="nama@email.com" autocomplete="email">
                        @error('email')<p class="text-red-400 text-xs mt-1.5 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i>{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-white/60 uppercase tracking-wider mb-2">Password</label>
                        <input type="password" name="password" required
                               class="input-dark" placeholder="••••••••" autocomplete="current-password">
                        @error('password')<p class="text-red-400 text-xs mt-1.5 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i>{{ $message }}</p>@enderror
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded-md border-white/20 bg-white/10 text-primary-500 focus:ring-primary-500/20">
                            <span class="text-white/50 text-xs">Ingat saya</span>
                        </label>
                    </div>

                    <button type="submit"
                            class="w-full mt-2 bg-primary-500 hover:bg-primary-400 text-white font-bold py-3 rounded-2xl transition-all duration-200 shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 hover:-translate-y-0.5 text-sm flex items-center justify-center gap-2">
                        <i data-lucide="log-in" class="w-4 h-4"></i> Masuk Sekarang
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-white/10 text-center space-y-3">
                    <p class="text-white/40 text-sm">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-primary-300 hover:text-primary-200 font-semibold transition-colors">Daftar gratis</a>
                    </p>
                    <a href="{{ route('catalog') }}" class="text-white/30 hover:text-white/50 text-xs flex items-center justify-center gap-1.5 transition-colors">
                        <i data-lucide="search" class="w-3.5 h-3.5"></i> Lihat katalog tanpa login
                    </a>
                </div>

                <!-- {{-- Demo hint --}}
                <div class="mt-5 p-3 bg-white/5 border border-white/10 rounded-2xl">
                    <p class="text-white/40 text-[11px] font-medium mb-1.5">Demo akun:</p>
                    <div class="grid grid-cols-2 gap-2 text-[11px] text-white/50">
                        <div><span class="text-white/30">Admin:</span> admin@perpustakaan.com</div>
                        <div><span class="text-white/30">Pass:</span> password</div>
                        <div><span class="text-white/30">Member:</span> budi@email.com</div>
                        <div><span class="text-white/30">Pass:</span> password</div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
</div>
@endsection
