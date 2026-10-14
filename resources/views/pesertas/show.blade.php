@extends('layouts.admin')

@section('title', 'Detail Peserta - ' . $peserta->nama)
@section('page-title', 'Detail Data Peserta')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    <div class="bg-white rounded-lg p-5 border border-gray-200 flex justify-between items-center">
        <div>
            <h2 class="text-base font-bold text-gray-900">{{ $peserta->nama }}</h2>
            <div class="text-xs text-gray-500 mt-0.5">
                NIK: {{ $peserta->nik }} &bull; Terdaftar: {{ $peserta->tanggal_daftar }}
            </div>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('pesertas.edit', $peserta->id) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded text-xs font-semibold">
                Edit
            </a>
            <a href="{{ route('pesertas.index') }}" class="px-3 py-1.5 border border-gray-300 rounded text-gray-700 text-xs font-semibold hover:bg-gray-50">
                &larr; Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider pb-2 mb-3 border-b border-gray-100">
            Rincian Data Diri & Sertifikasi
        </h3>

        <dl class="space-y-2 text-xs">
            <div class="flex justify-between py-1 border-b border-gray-50"><dt class="text-gray-500">Nama</dt><dd class="font-bold text-gray-900">{{ $peserta->nama }}</dd></div>
            <div class="flex justify-between py-1 border-b border-gray-50"><dt class="text-gray-500">NIK</dt><dd class="font-mono text-gray-800">{{ $peserta->nik }}</dd></div>
            <div class="flex justify-between py-1 border-b border-gray-50"><dt class="text-gray-500">Jenis Kelamin</dt><dd class="text-gray-800">{{ $peserta->jenis_kelamin }}</dd></div>
            <div class="flex justify-between py-1 border-b border-gray-50"><dt class="text-gray-500">Tempat, Tanggal Lahir</dt><dd class="text-gray-800">{{ $peserta->tempat_lahir ?? '-' }}, {{ $peserta->tanggal_lahir ?? '-' }}</dd></div>
            <div class="flex justify-between py-1 border-b border-gray-50"><dt class="text-gray-500">Email</dt><dd class="text-gray-800">{{ $peserta->email }}</dd></div>
            <div class="flex justify-between py-1 border-b border-gray-50"><dt class="text-gray-500">No. HP</dt><dd class="text-gray-800">{{ $peserta->no_hp ?? '-' }}</dd></div>
            <div class="flex justify-between py-1 border-b border-gray-50"><dt class="text-gray-500">Alamat</dt><dd class="text-gray-800 text-right max-w-xs">{{ $peserta->alamat }}</dd></div>
            <div class="flex justify-between py-1 border-b border-gray-50"><dt class="text-gray-500">Skema Sertifikasi</dt><dd class="font-semibold text-blue-700">{{ $peserta->skema->kode_skema ?? '-' }} - {{ $peserta->skema->nama_skema ?? '-' }}</dd></div>
            <div class="flex justify-between py-1 border-b border-gray-50"><dt class="text-gray-500">Status Kelulusan</dt><dd class="font-bold">{{ $peserta->status_kompetensi }}</dd></div>
            <div class="flex justify-between py-1"><dt class="text-gray-500">Penyelesaian Tugas</dt><dd class="text-gray-800">{{ $peserta->tugas_selesai_count }} / {{ $peserta->total_tugas }} Selesai ({{ $peserta->persentase_selesai }}%)</dd></div>
        </dl>
    </div>

</div>
@endsection
