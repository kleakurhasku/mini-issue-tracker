<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mini Issue Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">
    <nav class="bg-gradient-to-r from-sky-600 via-indigo-600 to-cyan-500 shadow-xl shadow-slate-900/10 mb-8">
        <div class="max-w-6xl mx-auto px-6 py-4 flex flex-wrap items-center gap-5">
            <a href="{{ route('projects.index') }}" class="font-semibold text-white text-lg tracking-wide">Issue Tracker</a>
            <a href="{{ route('projects.index') }}" class="text-slate-100 hover:text-white transition">Projects</a>
            <a href="{{ route('issues.index') }}" class="text-slate-100 hover:text-white transition">Issues</a>

            <div class="ml-auto flex flex-wrap items-center gap-3">
                @auth
                    <span class="text-sm text-slate-100 bg-white/10 px-3 py-1 rounded-full">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-white hover:text-slate-200 border border-white/20 px-3 py-1 rounded-full transition">Logout</button>
                    </form>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 pb-10">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-900 px-4 py-3 rounded-2xl shadow-sm mb-4">
                {{ session('success') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>