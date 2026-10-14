<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LSP-APP - Sistem Informasi Sertifikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('welcome') }}" class="font-bold text-lg text-gray-900 tracking-tight">
                LSP-APP
            </a>
            <div class="flex items-center gap-3 text-sm">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md font-medium hover:bg-blue-700">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 font-medium px-2 py-1">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md font-medium hover:bg-blue-700">
                        Daftar Peserta
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main -->
    <main class="flex-1 max-w-6xl mx-auto px-4 py-10 w-full">
        @if (session('success'))
            <div class="mb-6 p-3 rounded-md bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-lg p-6 sm:p-8 mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Sertifikasi Profesi Online</h1>
            <p class="text-gray-600 text-sm mt-2 max-w-2xl leading-relaxed">
                Aplikasi pendaftaran uji kompetensi dan pengumpulan portofolio tugas peserta sertifikasi.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('register') }}" class="px-4 py-2.5 bg-blue-600 text-white rounded-md text-sm font-semibold hover:bg-blue-700">
                    Daftar Sebagai Peserta
                </a>
                <a href="{{ route('login') }}" class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-md text-sm font-semibold hover:bg-gray-200">
                    Masuk ke Akun
                </a>
            </div>
        </div>

        <!-- Daftar Skema -->
        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-4">Skema Sertifikasi Tersedia</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($skemas as $skema)
                    <div class="bg-white border border-gray-200 rounded-lg p-5 flex flex-col justify-between">
                        <div>
                            <span class="text-xs font-mono font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">
                                {{ $skema->kode_skema }}
                            </span>
                            <h3 class="text-base font-bold text-gray-900 mt-2">{{ $skema->nama_skema }}</h3>
                            <p class="text-xs text-gray-600 mt-2 line-clamp-3 leading-relaxed">
                                {{ $skema->deskripsi ?? 'Tidak ada deskripsi.' }}
                            </p>
                        </div>
                        <div class="mt-5 pt-3 border-t border-gray-100 flex items-center justify-between text-xs">
                            <span class="text-gray-500">{{ $skema->pesertas_count ?? 0 }} Peserta</span>
                            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:underline">
                                Daftar &rarr;
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8 text-gray-400 bg-white border border-dashed border-gray-300 rounded-lg text-sm">
                        Belum ada skema sertifikasi.
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 py-4 text-center text-xs text-gray-500">
        &copy; {{ date('Y') }} LSP-APP. Sistem Informasi Uji Kompetensi.
    </footer>

</body>
</html>
