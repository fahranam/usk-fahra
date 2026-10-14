@extends('layouts.admin')

@section('title', 'Tambah Peserta')
@section('page-title', 'Tambah Data Peserta')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg p-5 sm:p-6 border border-gray-200">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100">
            <h2 class="text-sm font-bold text-gray-900">Formulir Tambah Peserta Baru</h2>
            <a href="{{ route('pesertas.index') }}" class="text-xs text-gray-600 hover:text-gray-900">&larr; Kembali</a>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                <span class="font-bold block mb-1">Periksa kembali data:</span>
                <ul class="list-disc list-inside space-y-0.5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pesertas.store') }}" method="POST" class="space-y-3.5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-rose-600">*</span></label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required
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
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email <span class="text-rose-600">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">No. HP <span class="text-rose-600">*</span></label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" maxlength="15" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Skema Sertifikasi <span class="text-rose-600">*</span></label>
                    <select name="skema_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:border-blue-600">
                        <option value="">-- Pilih Skema --</option>
                        @foreach ($skemas as $skema)
                            <option value="{{ $skema->id }}" @selected(old('skema_id') == $skema->id)>
                                {{ $skema->kode_skema }} - {{ $skema->nama_skema }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Tanggal Pendaftaran <span class="text-rose-600">*</span></label>
                    <input type="date" name="tanggal_daftar" value="{{ old('tanggal_daftar', date('Y-m-d')) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:border-blue-600">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Alamat Lengkap <span class="text-rose-600">*</span></label>
                    <textarea name="alamat" rows="2" required
                              class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">{{ old('alamat') }}</textarea>
                </div>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <a href="{{ route('pesertas.index') }}" class="px-3.5 py-2 border border-gray-300 rounded text-gray-700 text-xs font-semibold hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-semibold">
                    Simpan Peserta
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
