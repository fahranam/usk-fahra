@extends('layouts.admin')

@section('title', 'Penilaian - ' . $peserta->nama)
@section('page-title', 'Form Penilaian Asesor')

@section('content')
<div class="max-w-3xl mx-auto space-y-4">

    <div class="bg-white rounded-lg p-5 border border-gray-200 flex justify-between items-center">
        <div>
            <span class="font-mono text-xs font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded">
                {{ $tugas->skema->kode_skema }} - {{ $tugas->skema->nama_skema }}
            </span>
            <h2 class="text-base font-bold text-gray-900 mt-1.5">{{ $tugas->judul }}</h2>
            <div class="text-xs text-gray-500 mt-0.5">Asesi: <strong>{{ $peserta->nama }}</strong> ({{ $peserta->nik }})</div>
        </div>
        <a href="{{ route('admin.tugas.penilaian', $tugas->id) }}" class="px-3 py-1.5 border border-gray-300 rounded text-gray-700 text-xs font-semibold hover:bg-gray-50">
            &larr; Kembali
        </a>
    </div>

    <!-- Evidence -->
    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider pb-2 mb-3 border-b border-gray-100">
            Bukti Pengerjaan Asesi
        </h3>

        @if($pengumpulan && ($pengumpulan->link_jawaban || $pengumpulan->file_jawaban))
            <div class="space-y-3 text-xs">
                @if($pengumpulan->link_jawaban)
                    <div>
                        <span class="text-gray-500 block mb-1">Tautan Hasil:</span>
                        <a href="{{ $pengumpulan->link_jawaban }}" target="_blank" class="text-blue-600 font-semibold hover:underline break-all">
                            {{ $pengumpulan->link_jawaban }}
                        </a>
                    </div>
                @endif

                @if($pengumpulan->file_jawaban)
                    <div>
                        <span class="text-gray-500 block mb-1">Lampiran Gambar:</span>
                        <a href="{{ asset($pengumpulan->file_jawaban) }}" target="_blank">
                            <img src="{{ asset($pengumpulan->file_jawaban) }}" alt="Bukti" class="max-h-72 border rounded">
                        </a>
                    </div>
                @endif
            </div>
        @else
            <div class="py-4 text-center text-gray-400 text-xs italic">
                Peserta belum mengumpulkan link maupun gambar tugas.
            </div>
        @endif
    </div>

    <!-- Form Nilai -->
    <div class="bg-white rounded-lg p-5 border border-gray-200">
        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider pb-2 mb-3 border-b border-gray-100">
            Keputusan Asesor
        </h3>

        @if ($errors->any())
            <div class="mb-3 p-2.5 rounded bg-rose-50 border border-rose-200 text-rose-800 text-xs">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.tugas.simpanNilai', ['tugas' => $tugas->id, 'peserta' => $peserta->id]) }}" method="POST" class="space-y-3.5">
            @csrf

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-2">Status Kompetensi <span class="text-rose-600">*</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 text-xs font-semibold text-emerald-800 cursor-pointer">
                        <input type="radio" name="status" value="Kompeten" required
                               @checked(old('status', $pengumpulan?->status) === 'Kompeten')>
                        <span>Kompeten (K)</span>
                    </label>

                    <label class="flex items-center gap-2 text-xs font-semibold text-rose-800 cursor-pointer">
                        <input type="radio" name="status" value="Belum Kompeten" required
                               @checked(old('status', $pengumpulan?->status) === 'Belum Kompeten')>
                        <span>Belum Kompeten (BK)</span>
                    </label>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Catatan / Umpan Balik Asesor</label>
                <textarea name="catatan_admin" rows="3"
                          placeholder="Keterangan atau saran perbaikan..."
                          class="w-full px-3 py-2 border border-gray-300 rounded text-xs focus:outline-none focus:border-blue-600">{{ old('catatan_admin', $pengumpulan?->catatan_admin) }}</textarea>
            </div>

            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-semibold">
                    Simpan Keputusan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
