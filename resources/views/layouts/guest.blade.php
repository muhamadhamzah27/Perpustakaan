<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title','Perpustakaan Digital')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config={theme:{extend:{colors:{primary:{50:'#f0f4ff',100:'#e0eaff',200:'#c2d4ff',300:'#93b0fd',400:'#6388fb',500:'#4361f0',600:'#3248d9',700:'#2a3ab8',800:'#283494',900:'#272f77',950:'#1a1f4f'}},fontFamily:{sans:['Inter','system-ui','sans-serif']},animation:{'fade-up':'fadeUp .4s ease both','fade-in':'fadeIn .3s ease both'},keyframes:{fadeUp:{from:{opacity:'0',transform:'translateY(16px)'},to:{opacity:'1',transform:'translateY(0)'}},fadeIn:{from:{opacity:'0'},to:{opacity:'1'}}}}}}
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <style>
        body { font-family:'Inter',sans-serif; -webkit-font-smoothing:antialiased; }
        .glass { background:rgba(255,255,255,.06); backdrop-filter:blur(20px); border:1px solid rgba(255,255,255,.12); }
        .input-dark {
            width:100%; background:rgba(255,255,255,.08); border:1.5px solid rgba(255,255,255,.14);
            border-radius:.875rem; padding:.75rem 1rem; font-size:.875rem;
            color:#fff; outline:none; transition:all .2s;
        }
        .input-dark:focus { background:rgba(255,255,255,.12); border-color:rgba(99,136,251,.7); box-shadow:0 0 0 3px rgba(67,97,240,.2); }
        .input-dark::placeholder { color:rgba(255,255,255,.3); }
    </style>
</head>
<body class="h-full min-h-screen bg-[#0c0e1a]">
    {{-- Animated background --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-1/4 -left-1/4 w-[600px] h-[600px] bg-primary-600/20 rounded-full blur-[100px]"></div>
        <div class="absolute -bottom-1/4 -right-1/4 w-[500px] h-[500px] bg-indigo-500/15 rounded-full blur-[100px]"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[300px] h-[300px] bg-primary-500/10 rounded-full blur-[80px]"></div>
        {{-- Grid overlay --}}
        <div class="absolute inset-0 opacity-[.03]"
             style="background-image:linear-gradient(rgba(255,255,255,.5) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.5) 1px,transparent 1px);background-size:40px 40px"></div>
    </div>

    <div class="relative z-10">
        @yield('content')
    </div>

    <script>document.addEventListener('DOMContentLoaded',()=>lucide.createIcons())</script>
    @stack('scripts')
</body>
</html>
