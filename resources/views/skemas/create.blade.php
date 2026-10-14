@extends('layouts.admin')

@section('title', 'Tambah Skema')
@section('page-title', 'Tambah Skema Baru')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-lg p-5 sm:p-6 border border-gray-200">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100">
            <h2 class="text-sm font-bold text-gray-900">Formulir Skema Sertifikasi Baru</h2>
            <a href="{{ route('skemas.index') }}" class="text-xs text-gray-600 hover:text-gray-900">&larr; Kembali</a>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('skemas.store') }}" method="POST" class="space-y-3.5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Kode Skema <span class="text-rose-600">*</span></label>
                <input type="text" name="kode_skema" value="{{ old('kode_skema') }}" required
                       placeholder="Contoh: JWD-001"
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Skema <span class="text-rose-600">*</span></label>
                <input type="text" name="nama_skema" value="{{ old('nama_skema') }}" required
                       placeholder="Contoh: Junior Web Developer"
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Deskripsi Skema</label>
                <textarea name="deskripsi" rows="3"
                          placeholder="Keterangan kompetensi..."
                          class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <a href="{{ route('skemas.index') }}" class="px-3.5 py-2 border border-gray-300 rounded text-gray-700 text-xs font-semibold hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-semibold">
                    Simpan Skema
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
