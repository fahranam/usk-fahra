@extends('layouts.admin')

@section('title', 'Detail Skema - ' . $skema->nama_skema)
@section('page-title', 'Detail Skema Sertifikasi')

@section('content')
<div class="max-w-2xl mx-auto space-y-4">

    <div class="bg-white rounded-lg p-5 border border-gray-200 flex justify-between items-center">
        <div>
            <span class="font-mono text-xs font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">
                {{ $skema->kode_skema }}
            </span>
            <h2 class="text-base font-bold text-gray-900 mt-1.5">{{ $skema->nama_skema }}</h2>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('skemas.edit', $skema->id) }}" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded text-xs font-semibold">
                Edit
            </a>
            <a href="{{ route('skemas.index') }}" class="px-3 py-1.5 border border-gray-300 rounded text-gray-700 text-xs font-semibold hover:bg-gray-50">
                &larr; Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider pb-2 mb-2 border-b border-gray-100">
            Deskripsi Skema
        </h3>
        <p class="text-xs text-gray-600 leading-relaxed whitespace-pre-wrap">
            {{ $skema->deskripsi ?? 'Belum ada deskripsi.' }}
        </p>
    </div>

    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <div class="flex justify-between items-center pb-2 mb-3 border-b border-gray-100">
            <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Tugas Pada Skema Ini</h3>
            <a href="{{ route('admin.tugas.create', ['skema_id' => $skema->id]) }}" class="text-xs text-blue-600 hover:underline font-medium">
                + Tambah Tugas
            </a>
        </div>

        <div class="space-y-2">
            @forelse($skema->tugas as $tugas)
                <div class="p-3 bg-gray-50 border border-gray-100 rounded flex justify-between items-center text-xs">
                    <div>
                        <div class="font-bold text-gray-900">{{ $tugas->judul }}</div>
                        <div class="text-[11px] text-gray-500 line-clamp-1">{{ $tugas->deskripsi }}</div>
                    </div>
                    <a href="{{ route('admin.tugas.penilaian', $tugas->id) }}" class="px-2.5 py-1 bg-blue-600 text-white rounded font-medium hover:bg-blue-700 text-[11px] whitespace-nowrap">
                        Nilai Asesi &rarr;
                    </a>
                </div>
            @empty
                <div class="text-center py-4 text-xs text-gray-400">Belum ada tugas pada skema ini.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
