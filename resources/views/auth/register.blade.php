<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Peserta - LSP-APP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: system-ui, -apple-system, sans-serif; }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen flex items-center justify-center p-4 py-8">

    <div class="max-w-xl w-full bg-white rounded-lg border border-gray-200 p-6 sm:p-8 shadow-sm">
        <div class="mb-5">
            <a href="{{ route('welcome') }}" class="text-xs text-blue-600 hover:underline">&larr; Kembali ke Beranda</a>
            <h1 class="text-xl font-bold text-gray-900 mt-2">Pendaftaran Peserta Baru</h1>
            <p class="text-xs text-gray-500 mt-0.5">Isi data identitas diri dan skema yang ingin Anda ikuti</p>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <span class="font-bold block mb-1">Ada data yang belum sesuai:</span>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.process') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-rose-600">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">NIK (16 Digit) <span class="text-rose-600">*</span></label>
                    <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Jenis Kelamin <span class="text-rose-600">*</span></label>
                    <select name="jenis_kelamin" required
                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:border-blue-600">
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki" @selected(old('jenis_kelamin') == 'Laki-laki')>Laki-laki</option>
                        <option value="Perempuan" @selected(old('jenis_kelamin') == 'Perempuan')>Perempuan</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tempat Lahir <span class="text-rose-600">*</span></label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Lahir <span class="text-rose-600">*</span></label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email Aktif <span class="text-rose-600">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">No. HP / WhatsApp <span class="text-rose-600">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" maxlength="15" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pilihan Skema Sertifikasi <span class="text-rose-600">*</span></label>
                    <select name="skema_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:border-blue-600">
                        <option value="">-- Pilih Skema --</option>
                        @foreach ($skemas as $s)
                            <option value="{{ $s->id }}" @selected(old('skema_id') == $s->id)>
                                {{ $s->kode_skema }} - {{ $s->nama_skema }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Alamat Lengkap <span class="text-rose-600">*</span></label>
                    <textarea name="alamat" rows="2" required
                              class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">{{ old('alamat') }}</textarea>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Password (Min. 6) <span class="text-rose-600">*</span></label>
                    <input type="password" name="password" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Ulangi Password <span class="text-rose-600">*</span></label>
                    <input type="password" name="password_confirmation" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                </div>
            </div>

            <button type="submit"
                    class="w-full py-2.5 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-semibold transition-colors mt-2">
                Daftar Sekarang
            </button>
        </form>

        <div class="mt-4 pt-3 border-t border-gray-100 text-center text-xs text-gray-600">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold">Login di sini</a>
        </div>
    </div>

</body>
</html>
