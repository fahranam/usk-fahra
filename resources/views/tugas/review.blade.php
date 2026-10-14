@extends('layouts.admin')

@section('title', 'Review & Penilaian Tugas')
@section('page-title', 'Review Tugas & Penilaian Kompetensi')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-slate-900">Review & Penilaian Tugas Asesi</h2>
            <p class="text-xs text-slate-500 mt-0.5">Periksa hasil unjuk kerja dan tetapkan status Kompeten / Belum Kompeten</p>
        </div>
        <a href="{{ route('admin.tugas.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-colors">
            &larr; Kembali
        </a>
    </div>

    <!-- Details -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
        <h3 class="text-sm font-bold text-slate-900 pb-3 mb-4 border-b border-slate-100">Informasi Tugas</h3>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $tugas->judul ?? '-' }}</p>
    </div>

</div>
@endsection
