@extends('layouts.admin')

@section('title', 'Edit Tugas')
@section('page-title', 'Ubah Tugas Asesmen')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white rounded-lg p-5 sm:p-6 border border-gray-200">
        <div class="flex items-center justify-between pb-3 mb-4 border-b border-gray-100">
            <h2 class="text-sm font-bold text-gray-900">Edit Tugas: {{ $tugas->judul }}</h2>
            <a href="{{ route('admin.tugas.index') }}" class="text-xs text-gray-600 hover:text-gray-900">&larr; Kembali</a>
        </div>

        @if ($errors->any())
            <div class="mb-4 p-3 rounded bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.tugas.update', $tugas->id) }}" method="POST" class="space-y-3.5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Skema Target <span class="text-rose-600">*</span></label>
                <select name="skema_id" required
                        class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-white focus:outline-none focus:border-blue-600">
                    @foreach ($skemas as $s)
                        <option value="{{ $s->id }}" @selected(old('skema_id', $tugas->skema_id) == $s->id)>
                            {{ $s->kode_skema }} - {{ $s->nama_skema }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Judul Tugas <span class="text-rose-600">*</span></label>
                <input type="text" name="judul" value="{{ old('judul', $tugas->judul) }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Petunjuk & Deskripsi Tugas <span class="text-rose-600">*</span></label>
                <textarea name="deskripsi" rows="5" required
                          class="w-full px-3 py-2 border border-gray-300 rounded text-sm focus:outline-none focus:border-blue-600">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
            </div>

            <div class="pt-2 flex justify-end gap-2">
                <a href="{{ route('admin.tugas.index') }}" class="px-3.5 py-2 border border-gray-300 rounded text-gray-700 text-xs font-semibold hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-semibold">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
