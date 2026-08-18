<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Digital')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        50:  '#f0f4ff', 100: '#e0eaff', 200: '#c2d4ff',
                        300: '#93b0fd', 400: '#6388fb', 500: '#4361f0',
                        600: '#3248d9', 700: '#2a3ab8', 800: '#283494',
                        900: '#272f77', 950: '#1a1f4f',
                    },
                    surface: {
                        50:  '#f8fafc', 100: '#f1f5f9', 200: '#e2e8f0',
                        300: '#cbd5e1', 400: '#94a3b8', 500: '#64748b',
                        600: '#475569', 700: '#334155', 800: '#1e293b',
                        900: '#0f172a', 950: '#020617',
                    }
                },
                fontFamily: {
                    sans: ['Inter', 'system-ui', 'sans-serif'],
                },
                boxShadow: {
                    'card':   '0 1px 3px 0 rgb(0 0 0 / .04), 0 1px 2px -1px rgb(0 0 0 / .04)',
                    'card-md':'0 4px 16px -2px rgb(0 0 0 / .08), 0 2px 6px -2px rgb(0 0 0 / .05)',
                    'glow':   '0 0 0 3px rgb(67 97 240 / .15)',
                },
                borderRadius: {
                    '2xl': '1rem', '3xl': '1.5rem',
                },
                animation: {
                    'fade-up':   'fadeUp .35s ease both',
                    'fade-in':   'fadeIn .25s ease both',
                    'scale-in':  'scaleIn .2s ease both',
                },
                keyframes: {
                    fadeUp:  { from:{ opacity:'0', transform:'translateY(12px)' }, to:{ opacity:'1', transform:'translateY(0)' } },
                    fadeIn:  { from:{ opacity:'0' }, to:{ opacity:'1' } },
                    scaleIn: { from:{ opacity:'0', transform:'scale(.96)' }, to:{ opacity:'1', transform:'scale(1)' } },
                }
            }
        }
    }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; -webkit-font-smoothing: antialiased; }

        /* Sidebar */
        .nav-item {
            display: flex; align-items: center; gap: .625rem;
            padding: .5rem .75rem; border-radius: .75rem;
            font-size: .8125rem; font-weight: 500;
            color: #64748b; transition: all .15s ease;
            text-decoration: none; cursor: pointer;
        }
        .nav-item:hover  { background: #f0f4ff; color: #4361f0; }
        .nav-item.active { background: #4361f0; color: #fff; box-shadow: 0 4px 12px rgb(67 97 240/.3); }
        .nav-item.active:hover { background: #3248d9; }

        /* Card */
        .card { background:#fff; border:1px solid #e2e8f0; border-radius:1rem; box-shadow:0 1px 3px rgb(0 0 0/.04); }

        /* Badge */
        .badge { display:inline-flex; align-items:center; padding:.2rem .6rem; border-radius:9999px; font-size:.7rem; font-weight:600; letter-spacing:.02em; }

        /* Form controls */
        .form-input {
            width:100%; background:#f8fafc; border:1.5px solid #e2e8f0;
            border-radius:.75rem; padding:.625rem 1rem; font-size:.875rem;
            color:#0f172a; transition:border-color .15s, box-shadow .15s;
            outline:none;
        }
        .form-input:focus { border-color:#4361f0; box-shadow:0 0 0 3px rgb(67 97 240/.12); background:#fff; }
        .form-input::placeholder { color:#94a3b8; }

        .form-select {
            width:100%; background:#f8fafc; border:1.5px solid #e2e8f0;
            border-radius:.75rem; padding:.625rem 1rem; font-size:.875rem;
            color:#0f172a; outline:none; cursor:pointer;
            transition:border-color .15s, box-shadow .15s;
            appearance:none;
            background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
            background-repeat:no-repeat; background-position:right .75rem center;
            padding-right:2.5rem;
        }
        .form-select:focus { border-color:#4361f0; box-shadow:0 0 0 3px rgb(67 97 240/.12); }

        /* Button */
        .btn-primary {
            display:inline-flex; align-items:center; gap:.4rem;
            background:#4361f0; color:#fff; font-weight:600; font-size:.8125rem;
            padding:.5625rem 1.125rem; border-radius:.75rem; border:none; cursor:pointer;
            box-shadow:0 2px 8px rgb(67 97 240/.3);
            transition:all .15s ease;
        }
        .btn-primary:hover { background:#3248d9; box-shadow:0 4px 14px rgb(67 97 240/.4); transform:translateY(-1px); }
        .btn-primary:active { transform:none; box-shadow:0 2px 8px rgb(67 97 240/.3); }

        .btn-ghost {
            display:inline-flex; align-items:center; gap:.4rem;
            background:transparent; color:#475569; font-weight:500; font-size:.8125rem;
            padding:.5rem .875rem; border-radius:.75rem; border:1.5px solid #e2e8f0; cursor:pointer;
            transition:all .15s ease;
        }
        .btn-ghost:hover { background:#f8fafc; border-color:#cbd5e1; color:#334155; }

        /* Table */
        .data-table th { background:#f8fafc; font-size:.7rem; font-weight:600; color:#94a3b8; text-transform:uppercase; letter-spacing:.06em; padding:.75rem 1.25rem; text-align:left; border-bottom:1px solid #f1f5f9; }
        .data-table td { padding:.875rem 1.25rem; font-size:.8125rem; color:#334155; border-bottom:1px solid #f8fafc; vertical-align:middle; }
        .data-table tr:last-child td { border-bottom:none; }
        .data-table tr:hover td { background:#fafbff; }

        /* Scrollbar */
        ::-webkit-scrollbar { width:5px; height:5px; }
        ::-webkit-scrollbar-track { background:transparent; }
        ::-webkit-scrollbar-thumb { background:#e2e8f0; border-radius:9999px; }
        ::-webkit-scrollbar-thumb:hover { background:#cbd5e1; }

        /* Sidebar section label */
        .nav-section { font-size:.65rem; font-weight:700; color:#cbd5e1; text-transform:uppercase; letter-spacing:.1em; padding:.25rem .75rem; margin-top:.75rem; }

        [x-cloak] { display:none !important; }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-surface-50" x-data="{ sidebarOpen: window.innerWidth >= 1024 }">

<div class="flex h-full">

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen && window.innerWidth < 1024"
         x-cloak @click="sidebarOpen=false"
         class="fixed inset-0 z-20 bg-surface-900/40 backdrop-blur-sm lg:hidden"
         x-transition:enter="transition duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
    </div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
           class="fixed inset-y-0 left-0 z-30 w-60 bg-white border-r border-surface-100 flex flex-col transition-transform duration-300 ease-out lg:static lg:translate-x-0">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-surface-100/80">
            <div class="w-8 h-8 rounded-xl bg-primary-600 flex items-center justify-center shadow-md shadow-primary-500/30 flex-shrink-0">
                <i data-lucide="book-open" class="w-4 h-4 text-white" stroke-width="2.5"></i>
            </div>
            <div>
                <p class="text-[13px] font-bold text-surface-900 leading-none">Perpustakaan</p>
                <p class="text-[10px] text-surface-400 mt-0.5 font-medium">Digital Library</p>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto px-3 py-3">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard" class="w-4 h-4 flex-shrink-0"></i> Dashboard
            </a>
            <a href="{{ route('books.index') }}" class="nav-item {{ request()->routeIs('books.*') ? 'active' : '' }}">
                <i data-lucide="book" class="w-4 h-4 flex-shrink-0"></i> Katalog Buku
            </a>
            <a href="{{ route('loans.index') }}" class="nav-item {{ request()->routeIs('loans.*') ? 'active' : '' }}">
                <i data-lucide="arrow-left-right" class="w-4 h-4 flex-shrink-0"></i> Peminjaman
            </a>
            <a href="{{ route('reservations.index') }}" class="nav-item {{ request()->routeIs('reservations.*') ? 'active' : '' }}">
                <i data-lucide="bookmark" class="w-4 h-4 flex-shrink-0"></i> Reservasi
            </a>

            @auth
            @if(auth()->user()->isAdmin())
                <p class="nav-section">Admin Panel</p>
                <a href="{{ route('members.index') }}" class="nav-item {{ request()->routeIs('members.*') ? 'active' : '' }}">
                    <i data-lucide="users" class="w-4 h-4 flex-shrink-0"></i> Anggota
                </a>
                <a href="{{ route('categories.index') }}" class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i data-lucide="tag" class="w-4 h-4 flex-shrink-0"></i> Kategori
                </a>
                <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i data-lucide="bar-chart-3" class="w-4 h-4 flex-shrink-0"></i> Laporan
                </a>
            @endif

            <p class="nav-section">Akun</p>
            <a href="{{ route('profile') }}" class="nav-item {{ request()->routeIs('profile*') ? 'active' : '' }}">
                <i data-lucide="user-circle-2" class="w-4 h-4 flex-shrink-0"></i> Profil Saya
            </a>
            @if(auth()->user()->isMember())
            <a href="{{ route('member.card') }}" class="nav-item {{ request()->routeIs('member.card') ? 'active' : '' }}">
                <i data-lucide="credit-card" class="w-4 h-4 flex-shrink-0"></i> Kartu Anggota
            </a>
            @endif
            @endauth
        </nav>

        {{-- User footer --}}
        @auth
        <div class="p-3 border-t border-surface-100">
            <div class="flex items-center gap-2.5 px-2 py-2 rounded-xl hover:bg-surface-50 transition group cursor-default mb-1">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0 shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name,0,2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[12px] font-semibold text-surface-800 truncate leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] text-surface-400 mt-0.5 capitalize">{{ auth()->user()->role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="nav-item w-full text-red-500 hover:bg-red-50 hover:text-red-600">
                    <i data-lucide="log-out" class="w-4 h-4"></i> Keluar
                </button>
            </form>
        </div>
        @endauth
    </aside>

    {{-- Main --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top bar --}}
        <header class="bg-white/80 backdrop-blur border-b border-surface-100 px-5 py-3 flex items-center gap-4 sticky top-0 z-10">
            <button @click="sidebarOpen=!sidebarOpen" class="lg:hidden p-1.5 rounded-lg hover:bg-surface-100 transition text-surface-500">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            <div class="flex-1 min-w-0">
                <h1 class="text-sm font-semibold text-surface-900 truncate">@yield('page-title','Dashboard')</h1>
                @hasSection('breadcrumb')
                <p class="text-xs text-surface-400 truncate">@yield('breadcrumb')</p>
                @endif
            </div>
            <div class="flex items-center gap-2">@yield('header-actions')</div>
        </header>

        {{-- Alerts --}}
        @if(session('success') || session('error') || $errors->any())
        <div class="px-5 pt-4 space-y-2 animate-fade-in">
            @if(session('success'))
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-sm font-medium">
                <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 flex-shrink-0"></i>
                {{ session('success') }}
            </div>
            @endif
            @if(session('error'))
            <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl text-sm font-medium">
                <i data-lucide="alert-circle" class="w-4 h-4 text-red-500 flex-shrink-0"></i>
                {{ session('error') }}
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-2xl text-sm">
                <div class="flex items-center gap-2 font-semibold mb-1.5"><i data-lucide="alert-circle" class="w-4 h-4"></i> Terdapat kesalahan:</div>
                <ul class="space-y-0.5 list-disc list-inside text-red-700">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif
        </div>
        @endif

        <main class="flex-1 overflow-y-auto p-5">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>document.addEventListener('DOMContentLoaded',()=>lucide.createIcons())</script>
@stack('scripts')
</body>
</html>
