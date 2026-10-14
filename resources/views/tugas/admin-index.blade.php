@extends('layouts.admin')

@section('title', 'Tugas & Penilaian')
@section('page-title', 'Tugas & Penilaian Asesor')

@section('content')
<div class="space-y-5">

    <div class="bg-white p-4 rounded-lg border border-gray-200 flex justify-between items-center">
        <div>
            <h2 class="text-sm font-bold text-gray-900">Penugasan Uji Kompetensi Per Skema</h2>
            <p class="text-xs text-gray-500 mt-0.5">Tugas otomatis berlaku untuk semua peserta di skema terkait.</p>
        </div>
        <a href="{{ route('admin.tugas.create') }}" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-semibold">
            + Buat Tugas
        </a>
    </div>

    <div class="space-y-4">
        @forelse($skemas as $skema)
            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-4 py-2.5 border-b border-gray-200 flex justify-between items-center text-xs">
                    <div>
                        <span class="font-mono font-bold text-blue-700 mr-2">{{ $skema->kode_skema }}</span>
                        <strong class="text-gray-800">{{ $skema->nama_skema }}</strong>
                        <span class="text-gray-500 ml-2">({{ $skema->pesertas->count() }} Asesi)</span>
                    </div>
                    <a href="{{ route('admin.tugas.create', ['skema_id' => $skema->id]) }}" class="text-blue-600 hover:underline font-medium">
                        + Tambah Tugas
                    </a>
                </div>

                <div class="p-3">
                    @if($skema->tugas->isEmpty())
                        <div class="text-center py-4 text-xs text-gray-400">Belum ada tugas di skema ini.</div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead>
                                    <tr class="text-gray-500 border-b border-gray-100">
                                        <th class="pb-2 w-8">No</th>
                                        <th class="pb-2">Judul Tugas</th>
                                        <th class="pb-2">Pengumpulan</th>
                                        <th class="pb-2 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach($skema->tugas as $tugas)
                                        @php
                                            $totalTerkumpul = $tugas->pengumpulan->count();
                                            $totalKompeten = $tugas->pengumpulan->where('status', 'Kompeten')->count();
                                        @endphp
                                        <tr>
                                            <td class="py-2.5 text-gray-400">{{ $loop->iteration }}</td>
                                            <td class="py-2.5">
                                                <div class="font-bold text-gray-900">{{ $tugas->judul }}</div>
                                                <div class="text-[11px] text-gray-500 line-clamp-1">{{ $tugas->deskripsi }}</div>
                                            </td>
                                            <td class="py-2.5 text-[11px]">
                                                <span>{{ $totalTerkumpul }} kumpul</span> &bull;
                                                <span class="text-emerald-700 font-semibold">{{ $totalKompeten }} kompeten</span>
                                            </td>
                                            <td class="py-2.5 text-right whitespace-nowrap">
                                                <a href="{{ route('admin.tugas.penilaian', $tugas->id) }}" class="px-2.5 py-1 bg-blue-600 text-white rounded font-medium hover:bg-blue-700 mr-1.5">
                                                    Nilai
                                                </a>
                                                <a href="{{ route('admin.tugas.edit', $tugas->id) }}" class="text-gray-600 hover:underline mr-1.5">Edit</a>
                                                <form action="{{ route('admin.tugas.destroy', $tugas->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:underline">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-6 text-xs text-gray-400 bg-white border border-dashed rounded-lg">
                Belum ada data skema.
            </div>
        @endforelse
    </div>

</div>
@endsection
