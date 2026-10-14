@extends('layouts.admin')

@section('title', 'Penilaian - ' . $tugas->judul)
@section('page-title', 'Pemeriksaan Tugas Asesi')

@section('content')
<div class="space-y-4">

    <div class="bg-white rounded-lg p-5 border border-gray-200 flex justify-between items-center">
        <div>
            <span class="font-mono text-xs font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">
                {{ $tugas->skema->kode_skema }} - {{ $tugas->skema->nama_skema }}
            </span>
            <h2 class="text-base font-bold text-gray-900 mt-1.5">{{ $tugas->judul }}</h2>
        </div>
        <a href="{{ route('admin.tugas.index') }}" class="px-3 py-1.5 border border-gray-300 rounded text-gray-700 text-xs font-semibold hover:bg-gray-50">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 text-xs text-gray-700 leading-relaxed whitespace-pre-wrap">
        <strong class="text-gray-900 block mb-1">Petunjuk Tugas:</strong>
        {{ $tugas->deskripsi }}
    </div>

    <!-- Table Submissions -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold uppercase tracking-wider">
                <tr>
                    <th class="p-3 w-10 text-center">No</th>
                    <th class="p-3">Nama Asesi</th>
                    <th class="p-3">NIK</th>
                    <th class="p-3">Jawaban Terkumpul</th>
                    <th class="p-3">Waktu</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pesertas as $peserta)
                    @php $pengumpulan = $peserta->pengumpulanTugas->first(); @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 text-center text-gray-400">{{ $loop->iteration }}</td>
                        <td class="p-3">
                            <div class="font-bold text-gray-900">{{ $peserta->nama }}</div>
                            <div class="text-[11px] text-gray-400">{{ $peserta->email }}</div>
                        </td>
                        <td class="p-3 font-mono text-gray-600">{{ $peserta->nik }}</td>
                        <td class="p-3 text-[11px]">
                            @if($pengumpulan && ($pengumpulan->link_jawaban || $pengumpulan->file_jawaban))
                                @if($pengumpulan->link_jawaban)
                                    <div>Link: <a href="{{ $pengumpulan->link_jawaban }}" target="_blank" class="text-blue-600 hover:underline">Buka Tautan</a></div>
                                @endif
                                @if($pengumpulan->file_jawaban)
                                    <div>Gambar: <a href="{{ asset($pengumpulan->file_jawaban) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Berkas</a></div>
                                @endif
                            @else
                                <span class="text-gray-400 italic">Belum kumpul</span>
                            @endif
                        </td>
                        <td class="p-3 text-gray-500 text-[11px]">
                            {{ $pengumpulan ? $pengumpulan->updated_at->format('d/m H:i') : '-' }}
                        </td>
                        <td class="p-3">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold
                                @if($pengumpulan && $pengumpulan->status === 'Kompeten') bg-emerald-50 text-emerald-700 border border-emerald-200
                                @elseif($pengumpulan && $pengumpulan->status === 'Belum Kompeten') bg-rose-50 text-rose-700 border border-rose-200
                                @elseif($pengumpulan && $pengumpulan->status === 'Menunggu Penilaian') bg-amber-50 text-amber-700 border border-amber-200
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ $pengumpulan->status ?? 'Belum Kumpul' }}
                            </span>
                        </td>
                        <td class="p-3 text-right">
                            <a href="{{ route('admin.tugas.reviewPeserta', ['tugas' => $tugas->id, 'peserta' => $peserta->id]) }}"
                               class="px-2.5 py-1 bg-blue-600 hover:bg-blue-700 text-white rounded text-[11px] font-semibold">
                                Periksa &rarr;
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-400">Belum ada peserta di skema ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
