@extends('layouts.app')
@section('title', 'Catat Peminjaman')
@section('page-title', 'Catat Peminjaman Baru')
@section('breadcrumb', 'Peminjaman → Baru')

@section('content')
<div class="max-w-2xl">
    <form method="POST" action="{{ route('loans.store') }}" class="space-y-5">
        @csrf
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-semibold text-slate-800 mb-5 pb-3 border-b border-slate-50">Data Peminjaman</h3>
            <div class="space-y-5">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Anggota <span class="text-red-500">*</span></label>
                    <select name="user_id" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                        <option value="">Pilih anggota...</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ (old('user_id', $selectedUser?->id) == $user->id) ? 'selected' : '' }}>
                            {{ $user->name }} — {{ $user->member_id }}
                        </option>
                        @endforeach
                    </select>
                    @error('user_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Buku <span class="text-red-500">*</span></label>
                    <select name="book_id" required class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                        <option value="">Pilih buku yang tersedia...</option>
                        @foreach($books as $book)
                        <option value="{{ $book->id }}" {{ (old('book_id', $selectedBook?->id) == $book->id) ? 'selected' : '' }}>
                            {{ $book->title }} — {{ $book->available_copies }} tersedia
                        </option>
                        @endforeach
                    </select>
                    @error('book_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Jatuh Tempo <span class="text-red-500">*</span></label>
                    <input type="date" name="due_date" value="{{ old('due_date', \Carbon\Carbon::today()->addDays(7)->format('Y-m-d')) }}" required
                           min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}"
                           class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50">
                    <p class="text-xs text-slate-400 mt-1">Default: 7 hari dari sekarang. Denda Rp 1.000/hari keterlambatan.</p>
                    @error('due_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Catatan</label>
                    <textarea name="notes" rows="3"
                              class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 bg-slate-50 resize-none"
                              placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl transition shadow-sm">
                <i data-lucide="check-circle" class="w-4 h-4"></i> Konfirmasi Peminjaman
            </button>
            <a href="{{ route('loans.index') }}" class="flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium px-6 py-2.5 rounded-xl transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
