@extends('layouts.app')
@section('title','Profil Saya')
@section('page-title','Profil Saya')

@section('content')
<div class="max-w-2xl space-y-5">

    {{-- Profile Header --}}
    <div class="card p-6 animate-fade-up">
        <div class="flex items-center gap-4 mb-6 pb-5 border-b border-surface-100">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-lg font-extrabold shadow-lg shadow-primary-500/30">
                {{ strtoupper(substr($user->name,0,2)) }}
            </div>
            <div>
                <h2 class="font-extrabold text-surface-900 text-lg leading-tight">{{ $user->name }}</h2>
                <p class="text-xs text-surface-400 capitalize mt-0.5">{{ $user->role }}</p>
                @if($user->member_id)
                <span class="inline-block mt-1.5 font-mono text-[11px] bg-primary-50 text-primary-600 px-2.5 py-0.5 rounded-lg font-semibold">
                    {{ $user->member_id }}
                </span>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name',$user->name) }}" class="form-input">
                    @error('name')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="form-input opacity-50 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone',$user->phone) }}" class="form-input" placeholder="08xx-xxxx-xxxx">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">Alamat</label>
                    <textarea name="address" rows="2" class="form-input resize-none" placeholder="Alamat lengkap">{{ old('address',$user->address) }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn-primary">
                <i data-lucide="save" class="w-3.5 h-3.5"></i> Simpan Profil
            </button>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="card p-6 animate-fade-up" style="animation-delay:.08s">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-xl bg-surface-100 flex items-center justify-center">
                <i data-lucide="key-round" class="w-4 h-4 text-surface-600"></i>
            </div>
            <h3 class="font-bold text-surface-900 text-sm">Ubah Password</h3>
        </div>
        <form method="POST" action="{{ route('profile.password') }}" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">Password Saat Ini</label>
                <input type="password" name="current_password" class="form-input" placeholder="Password lama">
                @error('current_password')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">Password Baru</label>
                    <input type="password" name="password" class="form-input" placeholder="Min. 6 karakter">
                    @error('password')<p class="text-rose-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">Konfirmasi</label>
                    <input type="password" name="password_confirmation" class="form-input" placeholder="Ulangi password">
                </div>
            </div>
            <button type="submit" class="btn-ghost">
                <i data-lucide="key" class="w-3.5 h-3.5"></i> Update Password
            </button>
        </form>
    </div>
</div>
@endsection
