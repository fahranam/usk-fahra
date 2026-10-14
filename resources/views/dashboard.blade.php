@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Administrator')

@section('content')
<div class="space-y-6">

    <!-- KPI Simple Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <span class="text-xs text-gray-500 font-medium">Total Peserta</span>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $totalPeserta }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <span class="text-xs text-gray-500 font-medium">Skema Sertifikasi</span>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $totalSkema }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <span class="text-xs text-gray-500 font-medium">Total Tugas</span>
            <div class="text-2xl font-bold text-gray-900 mt-1">{{ $totalTugas ?? 0 }}</div>
        </div>
        <div class="bg-white p-4 rounded-lg border border-gray-200">
            <span class="text-xs text-gray-500 font-medium">Kompeten (Lulus)</span>
            <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $totalKompeten ?? 0 }}</div>
        </div>
    </div>

    <!-- Quick Buttons -->
    <div class="flex items-center gap-2 flex-wrap">
        <a href="{{ route('pesertas.create') }}" class="px-3.5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-semibold">
            + Tambah Peserta
        </a>
        <a href="{{ route('skemas.create') }}" class="px-3.5 py-2 bg-gray-800 hover:bg-gray-900 text-white rounded text-xs font-semibold">
            + Tambah Skema
        </a>
        <a href="{{ route('admin.tugas.create') }}" class="px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded text-xs font-semibold border border-gray-300">
            + Buat Tugas
        </a>
    </div>

    <!-- 2 Col: Recent Participants & Schemes -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Participants -->
        <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-5 py-3.5 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-800">Peserta Terbaru</h3>
                <a href="{{ route('pesertas.index') }}" class="text-xs text-blue-600 hover:underline">Lihat Semua &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-gray-50 text-gray-500 border-b border-gray-200 uppercase tracking-wider">
                        <tr>
                            <th class="px-4 py-2.5">Nama</th>
                            <th class="px-4 py-2.5">Skema</th>
                            <th class="px-4 py-2.5">Status</th>
                            <th class="px-4 py-2.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($pesertaTerbaru ?? [] as $p)
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-900">{{ $p->nama }}</div>
                                    <div class="text-gray-400 text-[11px]">{{ $p->email }}</div>
                                </td>
                                <td class="px-4 py-3 text-gray-600 font-mono text-[11px]">
                                    {{ $p->skema->kode_skema ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold
                                        @if($p->status_kompetensi === 'Kompeten') bg-emerald-50 text-emerald-700
                                        @elseif($p->status_kompetensi === 'Belum Kompeten') bg-rose-50 text-rose-700
                                        @elseif($p->status_kompetensi === 'Menunggu Penilaian') bg-amber-50 text-amber-700
                                        @else bg-gray-100 text-gray-600 @endif">
                                        {{ $p->status_kompetensi }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('pesertas.show', $p->id) }}" class="text-blue-600 hover:underline font-medium">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-gray-400">Belum ada peserta.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Schemes List -->
        <div class="bg-white rounded-lg border border-gray-200 p-5">
            <h3 class="text-sm font-bold text-gray-800 mb-3">Daftar Skema</h3>
            <div class="space-y-2.5">
                @forelse($skemas ?? [] as $sk)
                    <div class="p-2.5 rounded border border-gray-100 bg-gray-50 flex items-center justify-between text-xs">
                        <div>
                            <span class="font-mono font-semibold text-blue-700">{{ $sk->kode_skema }}</span>
                            <div class="font-medium text-gray-800 line-clamp-1">{{ $sk->nama_skema }}</div>
                        </div>
                        <span class="text-gray-500 font-semibold">{{ $sk->pesertas_count ?? 0 }}</span>
                    </div>
                @empty
                    <div class="text-center text-gray-400 text-xs py-4">Belum ada skema.</div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
