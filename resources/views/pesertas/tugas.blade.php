@extends('layouts.admin')

@section('title', 'Tugas Sertifikasi')
@section('page-title', 'Tugas Uji Kompetensi Saya')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">

    <!-- Status Box -->
    <div class="bg-white border border-gray-200 rounded-lg p-4 flex flex-col sm:flex-row justify-between sm:items-center gap-3">
        <div>
            <div class="text-xs text-gray-500 font-medium">Status Asesmen Anda:</div>
            <div class="text-lg font-bold text-gray-900 mt-0.5">{{ $peserta->status_kompetensi }}</div>
            <div class="text-xs text-gray-500 mt-0.5">
                Skema: {{ $peserta->skema->nama_skema ?? '-' }} ({{ $peserta->skema->kode_skema ?? '-' }})
            </div>
        </div>
        <div class="text-right">
            <span class="text-xl font-bold text-blue-600 block">{{ $peserta->persentase_selesai }}%</span>
            <span class="text-xs text-gray-400">{{ $peserta->tugas_selesai_count }}/{{ $peserta->total_tugas }} Selesai</span>
        </div>
    </div>

    @if ($errors->any())
        <div class="p-3 rounded bg-rose-50 border border-rose-200 text-rose-800 text-xs">
            {{ $errors->first() }}
        </div>
    @endif

    <!-- Tasks -->
    <div class="space-y-4">
        @forelse($daftarTugas as $tugas)
            @php $pengumpulan = $tugas->pengumpulan->first(); @endphp
            <div class="bg-white border border-gray-200 rounded-lg p-5">
                <div class="flex justify-between items-start gap-3 pb-3 border-b border-gray-100">
                    <div>
                        <span class="text-[11px] font-semibold text-gray-400 uppercase">Tugas #{{ $loop->iteration }}</span>
                        <h3 class="text-sm font-bold text-gray-900 mt-0.5">{{ $tugas->judul }}</h3>
                    </div>
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded
                        @if($pengumpulan && $pengumpulan->status === 'Kompeten') bg-emerald-100 text-emerald-800
                        @elseif($pengumpulan && $pengumpulan->status === 'Belum Kompeten') bg-rose-100 text-rose-800
                        @elseif($pengumpulan && $pengumpulan->status === 'Menunggu Penilaian') bg-amber-100 text-amber-800
                        @else bg-gray-100 text-gray-600 @endif">
                        {{ $pengumpulan->status ?? 'Belum Dikumpulkan' }}
                    </span>
                </div>

                <div class="py-3 text-xs text-gray-600 leading-relaxed whitespace-pre-wrap">
                    {{ $tugas->deskripsi }}
                </div>

                @if($pengumpulan && ($pengumpulan->link_jawaban || $pengumpulan->file_jawaban))
                    <div class="p-3 bg-gray-50 border border-gray-100 rounded text-xs space-y-1 my-2">
                        <div class="font-semibold text-gray-700">Terkumpul:</div>
                        @if($pengumpulan->link_jawaban)
                            <div>Link: <a href="{{ $pengumpulan->link_jawaban }}" target="_blank" class="text-blue-600 hover:underline">{{ $pengumpulan->link_jawaban }}</a></div>
                        @endif
                        @if($pengumpulan->file_jawaban)
                            <div>File: <a href="{{ asset($pengumpulan->file_jawaban) }}" target="_blank" class="text-blue-600 hover:underline">Lihat Lampiran</a></div>
                        @endif
                        @if($pengumpulan->catatan_admin)
                            <div class="text-amber-800 mt-1.5 pt-1.5 border-t border-gray-200">
                                <strong>Catatan Asesor:</strong> {{ $pengumpulan->catatan_admin }}
                            </div>
                        @endif
                    </div>
                @endif

                <form action="{{ route('peserta.tugas.submit', $tugas->id) }}" method="POST" enctype="multipart/form-data"
                      class="pt-3 border-t border-gray-100 mt-3 space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-medium text-gray-600 mb-1">Tautan Hasil (URL GitHub / Web):</label>
                            <input type="url" name="link_jawaban" value="{{ old('link_jawaban', $pengumpulan?->link_jawaban) }}"
                                   placeholder="https://..."
                                   class="w-full px-2.5 py-1.5 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-600">
                        </div>
                        <div>
                            <label class="block text-[11px] font-medium text-gray-600 mb-1">Bukti Gambar / Screenshot (Maks 3MB):</label>
                            <input type="file" name="gambar_jawaban" accept="image/png,image/jpeg,image/webp"
                                   class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded file:border file:border-gray-300 file:bg-gray-50 file:text-xs">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-semibold">
                            {{ $pengumpulan ? 'Simpan Pembaruan' : 'Kumpulkan Tugas' }}
                        </button>
                    </div>
                </form>
            </div>
        @empty
            <div class="text-center py-8 text-xs text-gray-400 bg-white border border-dashed rounded-lg">
                Belum ada tugas untuk skema ini.
            </div>
        @endforelse
    </div>

</div>
@endsection
