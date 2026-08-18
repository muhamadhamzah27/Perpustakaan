@extends('layouts.guest')
@section('title','Daftar — Perpustakaan Digital')

@section('content')
<div class="min-h-screen flex items-center justify-center p-6 py-12">
    <div class="w-full max-w-lg">
        <div class="text-center mb-8 animate-fade-in">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-primary-500 mb-4 shadow-lg shadow-primary-500/40">
                <i data-lucide="user-plus" class="w-6 h-6 text-white"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Buat Akun Baru</h1>
            <p class="text-white/40 text-sm mt-1.5">Daftar sebagai anggota perpustakaan digital</p>
        </div>

        <div class="glass rounded-3xl p-8 shadow-2xl animate-fade-up">
            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-white/60 uppercase tracking-wider mb-2">Nama Lengkap <span class="text-rose-400">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                               class="input-dark" placeholder="Nama lengkap Anda" autocomplete="name">
                        @error('name')<p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-white/60 uppercase tracking-wider mb-2">Email <span class="text-rose-400">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="input-dark" placeholder="nama@email.com" autocomplete="email">
                        @error('email')<p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-white/60 uppercase tracking-wider mb-2">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="input-dark" placeholder="08xx-xxxx-xxxx">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-white/60 uppercase tracking-wider mb-2">Alamat</label>
                        <textarea name="address" rows="2" class="input-dark resize-none" placeholder="Alamat lengkap">{{ old('address') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-white/60 uppercase tracking-wider mb-2">Password <span class="text-rose-400">*</span></label>
                            <input type="password" name="password" required
                                   class="input-dark" placeholder="Min. 6 karakter" autocomplete="new-password">
                            @error('password')<p class="text-rose-400 text-xs mt-1.5">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-white/60 uppercase tracking-wider mb-2">Konfirmasi <span class="text-rose-400">*</span></label>
                            <input type="password" name="password_confirmation" required
                                   class="input-dark" placeholder="Ulangi password" autocomplete="new-password">
                        </div>
                    </div>
                </div>

                <button type="submit"
                        class="w-full mt-2 bg-primary-500 hover:bg-primary-400 text-white font-bold py-3 rounded-2xl transition-all duration-200 shadow-lg shadow-primary-500/30 hover:shadow-primary-500/50 hover:-translate-y-0.5 text-sm flex items-center justify-center gap-2">
                    <i data-lucide="user-check" class="w-4 h-4"></i> Daftar Sekarang
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-white/10 text-center">
                <p class="text-white/40 text-sm">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-primary-300 hover:text-primary-200 font-semibold transition-colors">Masuk</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
