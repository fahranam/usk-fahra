@extends('layouts.admin')

@section('title', 'Profil & Ganti Password')
@section('page-title', 'Pengaturan Akun & Password')

@section('content')
<div class="max-w-2xl mx-auto space-y-5">

    <!-- Card 1: Data Profil -->
    <div class="bg-white border border-gray-200 rounded-lg p-5">
        <h2 class="text-sm font-bold text-gray-900 pb-2 mb-4 border-b border-gray-100">
            {{ $user->role === 'admin' ? 'Data Akun Administrator' : 'Biodata Diri Peserta' }}
        </h2>

        <form action="{{ route('peserta.profil.update') }}" method="POST" class="space-y-3.5">
            @csrf
            @method('PUT')

            @if($user->role === 'admin')
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="{{ old('nama', $user->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                    @error('nama') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                    @error('email') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('nama', $peserta->nama) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                        @error('nama') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">NIK</label>
                        <input type="text" name="nik" value="{{ old('nik', $peserta->nik) }}" maxlength="16" required
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                        @error('nik') <span class="text-rose-600 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Jenis Kelamin</label>
                        <select name="jenis_kelamin" required
                                class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:border-blue-600">
                            <option value="Laki-laki" @selected(old('jenis_kelamin', $peserta->jenis_kelamin) == 'Laki-laki')>Laki-laki</option>
                            <option value="Perempuan" @selected(old('jenis_kelamin', $peserta->jenis_kelamin) == 'Perempuan')>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $peserta->tempat_lahir) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $peserta->tanggal_lahir) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $peserta->email) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">No. HP</label>
                        <input type="text" name="no_hp" value="{{ old('no_hp', $peserta->no_hp) }}" maxlength="15" required
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Skema Sertifikasi</label>
                        <select name="skema_id" required
                                class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:border-blue-600">
                            @foreach ($skemas as $skema)
                                <option value="{{ $skema->id }}" @selected(old('skema_id', $peserta->skema_id) == $skema->id)>
                                    {{ $skema->kode_skema }} - {{ $skema->nama_skema }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Alamat Lengkap</label>
                        <textarea name="alamat" rows="2" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">{{ old('alamat', $peserta->alamat) }}</textarea>
                    </div>
                </div>
            @endif

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <!-- Card 2: Form Ganti Password -->
    <div class="bg-white border border-gray-200 rounded-lg p-5">
        <h2 class="text-sm font-bold text-gray-900 pb-2 mb-4 border-b border-gray-100">
            Ubah Kata Sandi (Password)
        </h2>

        <form action="{{ route('peserta.password.update') }}" method="POST" class="space-y-3.5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Password Baru (Minimal 6 Karakter) <span class="text-rose-600">*</span></label>
                <input type="password" name="password" required autocomplete="new-password"
                       placeholder="Masukkan password baru"
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                @error('password')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Konfirmasi Password Baru <span class="text-rose-600">*</span></label>
                <input type="password" name="password_confirmation" required autocomplete="new-password"
                       placeholder="Ulangi password baru"
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                @error('password_confirmation')
                    <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-gray-800 hover:bg-gray-900 text-white rounded text-xs font-semibold">
                    Ganti Password Sekarang
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
