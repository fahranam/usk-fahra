@extends('layouts.admin')

@section('title', 'Data Skema')
@section('page-title', 'Data Skema Sertifikasi Profesi')

@section('content')
<div class="space-y-6">

    <!-- Header Card -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Master Data Skema Sertifikasi</h2>
            <p class="text-xs text-slate-500 mt-0.5">Daftar unit kompetensi dan paket sertifikasi yang terdaftar di LSP-APP</p>
        </div>
        <div>
            <a href="{{ route('skemas.create') }}"
               class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold flex items-center gap-2 shadow-md shadow-blue-500/20 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                <span>+ Tambah Skema Baru</span>
            </a>
        </div>
    </div>

    <!-- Schemes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($skemas as $skema)
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs hover:border-blue-300 hover:shadow-md transition-all flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-200">
                            {{ $skema->kode_skema }}
                        </span>
                        <div class="flex items-center gap-2 text-[11px] text-slate-400 font-medium">
                            <span>{{ $skema->pesertas_count ?? 0 }} Peserta</span>
                            <span>&bull;</span>
                            <span>{{ $skema->tugas_count ?? 0 }} Tugas</span>
                        </div>
                    </div>

                    <h3 class="text-base font-bold text-slate-900 mb-2 leading-snug">
                        {{ $skema->nama_skema }}
                    </h3>

                    <p class="text-xs text-slate-600 leading-relaxed mb-6 line-clamp-3">
                        {{ $skema->deskripsi ?? 'Belum ada deskripsi untuk skema ini.' }}
                    </p>
                </div>

                <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                    <a href="{{ route('skemas.show', $skema->id) }}" class="text-xs font-bold text-blue-600 hover:text-blue-700">
                        Lihat Detail &rarr;
                    </a>

                    <div class="flex items-center gap-1.5">
                        <a href="{{ route('skemas.edit', $skema->id) }}"
                           class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                            Edit
                        </a>
                        <form action="{{ route('skemas.destroy', $skema->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Hapus skema {{ $skema->nama_skema }}? Seluruh peserta dan tugas pada skema ini akan terhapus.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition-colors">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-2xl border border-dashed border-slate-300 text-slate-400">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span class="font-bold text-slate-700 block text-sm">Belum ada skema sertifikasi</span>
                <span class="text-xs text-slate-500 mt-1 block">Silakan tambahkan skema pertama untuk memulai.</span>
            </div>
        @endforelse
    </div>

</div>
@endsection
