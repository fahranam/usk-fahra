<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - LSP-APP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: system-ui, -apple-system, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; }
    </style>
    @yield('css')
</head>
<body class="bg-gray-100 text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- Topbar Mobile -->
    <header class="lg:hidden bg-slate-900 text-white px-4 py-3 flex items-center justify-between border-b border-slate-800">
        <span class="font-bold text-sm tracking-wide">LSP-APP</span>
        <button id="menuBtn" class="text-gray-300 hover:text-white p-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </header>

    <div class="flex-1 flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-40 w-56 bg-slate-900 text-gray-300 transform -translate-x-full lg:translate-x-0 lg:static transition-transform duration-150 flex flex-col border-r border-slate-800">
            <!-- Brand -->
            <div class="h-14 px-5 flex items-center justify-between border-b border-slate-800">
                <a href="{{ route('dashboard') }}" class="font-bold text-white text-base tracking-wide">
                    LSP-APP
                </a>
                <span class="text-[11px] px-2 py-0.5 rounded bg-slate-800 text-gray-400 font-mono">
                    {{ auth()->user()->role === 'admin' ? 'Admin' : 'Asesi' }}
                </span>
            </div>

            <!-- Nav -->
            <nav class="flex-1 p-3 space-y-1 text-sm overflow-y-auto">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-2.5 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('dashboard*') && !request()->routeIs('dashboard.peserta') ? 'bg-blue-600 text-white font-medium' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                    <span>Dashboard</span>
                </a>

                @if(auth()->user()->role === 'admin')
                    <div class="pt-3 pb-1 px-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Manajemen</div>
                    <a href="{{ route('pesertas.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('pesertas.*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>Data Peserta</span>
                    </a>
                    <a href="{{ route('skemas.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('skemas.*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>Data Skema</span>
                    </a>
                    <a href="{{ route('admin.tugas.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.tugas.*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>Tugas & Penilaian</span>
                    </a>
                    <div class="pt-3 pb-1 px-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Pengaturan</div>
                    <a href="{{ route('peserta.profil.edit') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('peserta.profil.*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>Akun & Password</span>
                    </a>
                @else
                    <div class="pt-3 pb-1 px-3 text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Uji Kompetensi</div>
                    <a href="{{ route('peserta.tugas.index') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('peserta.tugas.*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>Tugas Sertifikasi</span>
                    </a>
                    <a href="{{ route('peserta.profil.edit') }}"
                       class="flex items-center gap-2.5 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('peserta.profil.*') ? 'bg-blue-600 text-white font-medium' : 'text-gray-300 hover:bg-slate-800 hover:text-white' }}">
                        <span>Profil & Password</span>
                    </a>
                @endif
            </nav>

            <!-- User Info & Logout -->
            <div class="p-3 border-t border-slate-800 text-xs">
                <a href="{{ route('peserta.profil.edit') }}" class="block px-2 py-1.5 mb-2 hover:bg-slate-800 rounded transition-colors" title="Ubah profil & password">
                    <div class="font-medium text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-gray-400 truncate text-[11px]">{{ auth()->user()->email }}</div>
                </a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-2 py-1.5 rounded text-rose-400 hover:bg-slate-800 hover:text-rose-300 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Header Desktop -->
            <header class="hidden lg:flex h-14 bg-white border-b border-gray-200 px-6 items-center justify-between">
                <h1 class="text-base font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                <div class="flex items-center gap-4 text-xs">
                    <a href="{{ route('welcome') }}" target="_blank" class="text-gray-500 hover:text-gray-800">Halaman Depan &rarr;</a>
                    <span class="text-gray-300">|</span>
                    <span class="text-gray-600">{{ auth()->user()->name }}</span>
                </div>
            </header>

            <main class="flex-1 p-4 sm:p-6 max-w-6xl w-full mx-auto">
                @if(session('success'))
                    <div class="mb-4 p-3 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 p-3 rounded-md bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        const btn = document.getElementById('menuBtn');
        const sidebar = document.getElementById('sidebar');
        if (btn && sidebar) {
            btn.addEventListener('click', () => sidebar.classList.toggle('-translate-x-full'));
        }
    </script>
    @yield('scripts')
</body>
</html>
