<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - LSP-APP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-sm w-full bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
        <div class="mb-5">
            <a href="{{ route('welcome') }}" class="text-xs text-blue-600 hover:underline">&larr; Kembali ke Beranda</a>
            <h1 class="text-xl font-bold text-gray-900 mt-2">Login LSP-APP</h1>
            <p class="text-xs text-gray-500 mt-0.5">Masuk menggunakan email dan password akun Anda</p>
        </div>

        @if(session('success'))
            <div class="mb-4 p-2.5 rounded bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 p-2.5 rounded bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" class="space-y-3.5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
            </div>

            <button type="submit"
                    class="w-full py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-semibold transition-colors mt-1">
                Masuk
            </button>
        </form>

        <div class="mt-4 pt-3 border-t border-gray-100 text-center text-xs text-gray-600">
            Belum punya akun peserta?
            <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-semibold">Daftar di sini</a>
        </div>
    </div>

</body>
</html>
