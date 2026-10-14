<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lengkapi Profil - LSP-APP</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 text-slate-800 antialiased font-sans min-h-screen flex items-center justify-center p-4 selection:bg-blue-600 selection:text-white">

    <div class="max-w-xl w-full bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-200/80 p-8 sm:p-10 my-6">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-blue-600 text-white font-black text-2xl shadow-md shadow-blue-500/20 mb-3">
                L
            </div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Lengkapi Biodata Peserta</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Halo, {{ $user->name }}! Masukkan data KTP dan skema untuk memulai asesmen</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm">
                <span class="font-bold block mb-1">Periksa isian berikut:</span>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('peserta.profil.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">NIK (16 Digit) <span class="text-rose-500">*</span></label>
                <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" required
                       class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all placeholder:text-slate-400"
                       placeholder="Nomor Induk Kependudukan">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Kelamin <span class="text-rose-500">*</span></label>
                <select name="jenis_kelamin" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all bg-white">
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki" @selected(old('jenis_kelamin') == 'Laki-laki')>Laki-laki</option>
                    <option value="Perempuan" @selected(old('jenis_kelamin') == 'Perempuan')>Perempuan</option>
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all placeholder:text-slate-400"
                           placeholder="Kota lahir">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all bg-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Skema Sertifikasi <span class="text-rose-500">*</span></label>
                <select name="skema_id" required
                        class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all bg-white">
                    <option value="">-- Pilih Skema Sertifikasi --</option>
                    @foreach ($skemas as $s)
                        <option value="{{ $s->id }}" @selected(old('skema_id') == $s->id)>
                            {{ $s->kode_skema }} - {{ $s->nama_skema }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Tempat Tinggal <span class="text-rose-500">*</span></label>
                <textarea name="alamat" rows="3" required
                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition-all placeholder:text-slate-400"
                          placeholder="Alamat domisili lengkap...">{{ old('alamat') }}</textarea>
            </div>

            <button type="submit"
                    class="w-full py-3 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm shadow-md shadow-blue-500/20 transition-all active:scale-[0.99] mt-4">
                Simpan & Masuk Dashboard
            </button>
        </form>
    </div>

</body>
</html>
