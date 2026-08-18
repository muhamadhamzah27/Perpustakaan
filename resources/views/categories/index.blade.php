@extends('layouts.app')
@section('title','Kategori Buku')
@section('page-title','Kategori Buku')

@section('header-actions')
<button onclick="document.getElementById('modalAdd').classList.remove('hidden')" class="btn-primary">
    <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah Kategori
</button>
@endsection

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @forelse($categories as $i => $cat)
    <div class="card p-5 hover:shadow-card-md transition-shadow group animate-fade-up" style="animation-delay:{{ $i*.04 }}s">
        <div class="flex items-start justify-between mb-4">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center"
                 style="background:{{ $cat->color }}18">
                <div class="w-4 h-4 rounded-full shadow-sm" style="background:{{ $cat->color }}"></div>
            </div>
            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <button onclick="openEdit({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}', '{{ $cat->color }}')"
                        class="w-7 h-7 rounded-lg hover:bg-surface-100 flex items-center justify-center text-surface-500 transition-colors">
                    <i data-lucide="pencil" class="w-3.5 h-3.5"></i>
                </button>
                <form method="POST" action="{{ route('categories.destroy',$cat) }}"
                      onsubmit="return confirm('Hapus kategori ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-7 h-7 rounded-lg hover:bg-rose-50 flex items-center justify-center text-rose-500 transition-colors">
                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                    </button>
                </form>
            </div>
        </div>
        <h3 class="font-bold text-surface-800 text-sm mb-1">{{ $cat->name }}</h3>
        @if($cat->description)
        <p class="text-[11px] text-surface-400 line-clamp-2 mb-3 leading-relaxed">{{ $cat->description }}</p>
        @else
        <div class="mb-3"></div>
        @endif
        <span class="badge text-[11px]"
              style="background:{{ $cat->color }}15;color:{{ $cat->color }}">
            {{ $cat->books_count }} buku
        </span>
    </div>
    @empty
    <div class="col-span-4 card p-16 text-center text-surface-400">
        <i data-lucide="tag" class="w-10 h-10 mx-auto mb-2 opacity-30"></i>
        <p class="text-sm font-medium">Belum ada kategori</p>
    </div>
    @endforelse
</div>

{{-- Modal Add --}}
<div id="modalAdd" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-surface-900/50 backdrop-blur-sm" onclick="document.getElementById('modalAdd').classList.add('hidden')"></div>
    <div class="relative card w-full max-w-sm p-6 shadow-2xl animate-scale-in">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-surface-900">Tambah Kategori</h3>
            <button onclick="document.getElementById('modalAdd').classList.add('hidden')"
                    class="w-7 h-7 rounded-lg hover:bg-surface-100 flex items-center justify-center text-surface-500">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">Nama <span class="text-rose-500">*</span></label>
                <input type="text" name="name" required class="form-input" placeholder="Fiksi, Sains, Sejarah…">
            </div>
            <div>
                <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">Deskripsi</label>
                <textarea name="description" rows="2" class="form-input resize-none" placeholder="Deskripsi singkat…"></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">Warna Label</label>
                <div class="flex items-center gap-3">
                    <input type="color" name="color" value="#4361f0"
                           class="w-10 h-10 rounded-xl border-2 border-surface-200 cursor-pointer p-0.5">
                    <p class="text-xs text-surface-400">Pilih warna untuk badge kategori</p>
                </div>
            </div>
            <div class="flex gap-2 pt-1">
                <button type="submit" class="btn-primary flex-1 justify-center">Simpan</button>
                <button type="button" onclick="document.getElementById('modalAdd').classList.add('hidden')"
                        class="btn-ghost flex-1 justify-center">Batal</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit --}}
<div id="modalEdit" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-surface-900/50 backdrop-blur-sm" onclick="document.getElementById('modalEdit').classList.add('hidden')"></div>
    <div class="relative card w-full max-w-sm p-6 shadow-2xl animate-scale-in">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-bold text-surface-900">Edit Kategori</h3>
            <button onclick="document.getElementById('modalEdit').classList.add('hidden')"
                    class="w-7 h-7 rounded-lg hover:bg-surface-100 flex items-center justify-center text-surface-500">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>
        <form id="editForm" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">Nama</label>
                <input type="text" id="editName" name="name" required class="form-input">
            </div>
            <div>
                <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">Deskripsi</label>
                <textarea id="editDesc" name="description" rows="2" class="form-input resize-none"></textarea>
            </div>
            <div>
                <label class="block text-xs font-bold text-surface-500 uppercase tracking-wider mb-2">Warna Label</label>
                <input type="color" id="editColor" name="color"
                       class="w-10 h-10 rounded-xl border-2 border-surface-200 cursor-pointer p-0.5">
            </div>
            <div class="flex gap-2 pt-1">
                <button type="submit" class="btn-primary flex-1 justify-center">Simpan</button>
                <button type="button" onclick="document.getElementById('modalEdit').classList.add('hidden')"
                        class="btn-ghost flex-1 justify-center">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openEdit(id, name, desc, color) {
    document.getElementById('editName').value  = name;
    document.getElementById('editDesc').value  = desc;
    document.getElementById('editColor').value = color;
    document.getElementById('editForm').action = `/categories/${id}`;
    document.getElementById('modalEdit').classList.remove('hidden');
}
@if($errors->any())
document.getElementById('modalAdd').classList.remove('hidden');
@endif
</script>
@endpush
