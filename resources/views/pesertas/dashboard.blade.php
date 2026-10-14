@extends('layouts.admin')

@section('title', 'Dashboard Peserta')
@section('page-title', 'Dashboard Asesi')

@section('content')
<div class="space-y-6">

    <!-- Status Box -->
    <div class="bg-white border border-gray-200 rounded-lg p-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-gray-100">
            <div>
                <h2 class="text-base font-bold text-gray-900">Halo, {{ $peserta->nama }}</h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    Skema: <strong>{{ $peserta->skema->nama_skema ?? '-' }}</strong> ({{ $peserta->skema->kode_skema ?? '-' }})
                </p>
            </div>
            <div>
                <span class="inline-block px-3 py-1 rounded text-xs font-semibold
                    @if($peserta->status_kompetensi === 'Kompeten') bg-emerald-100 text-emerald-800
                    @elseif($peserta->status_kompetensi === 'Belum Kompeten') bg-rose-100 text-rose-800
                    @elseif($peserta->status_kompetensi === 'Menunggu Penilaian') bg-amber-100 text-amber-800
                    @else bg-gray-100 text-gray-700 @endif">
                    {{ $peserta->status_kompetensi }}
                </span>
            </div>
        </div>

        <!-- Progress -->
        <div class="mt-4">
            <div class="flex justify-between text-xs text-gray-600 mb-1 font-medium">
                <span>Pengerjaan Tugas: {{ $peserta->tugas_selesai_count }} / {{ $peserta->total_tugas }} Selesai</span>
                <span>{{ $peserta->persentase_selesai }}%</span>
            </div>
            <div class="w-full bg-gray-100 rounded h-2 overflow-hidden">
                <div class="bg-blue-600 h-2" style="width: {{ $peserta->persentase_selesai }}%;"></div>
            </div>
        </div>
    </div>

    <!-- 2 Col: Biodata & Skema -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Biodata -->
        <div class="bg-white border border-gray-200 rounded-lg p-5">
            <div class="flex justify-between items-center mb-3 pb-2 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800">Biodata Peserta</h3>
                <a href="{{ route('peserta.profil.edit') }}" class="text-xs text-blue-600 hover:underline">Edit</a>
            </div>
            <dl class="space-y-2 text-xs">
                <div class="flex justify-between"><dt class="text-gray-500">Nama</dt><dd class="font-semibold text-gray-800">{{ $peserta->nama }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">NIK</dt><dd class="text-gray-800 font-mono">{{ $peserta->nik }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">JK</dt><dd class="text-gray-800">{{ $peserta->jenis_kelamin }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="text-gray-800">{{ $peserta->email }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">No. HP</dt><dd class="text-gray-800">{{ $peserta->no_hp ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Alamat</dt><dd class="text-gray-800 text-right max-w-xs truncate">{{ $peserta->alamat }}</dd></div>
            </dl>
        </div>

        <!-- Tugas Action Card -->
        <div class="bg-white border border-gray-200 rounded-lg p-5 flex flex-col justify-between">
            <div>
                <h3 class="text-sm font-bold text-gray-800 mb-2">Tugas Uji Kompetensi</h3>
                <p class="text-xs text-gray-600 leading-relaxed mb-4">
                    Kumpulkan tautan hasil pengerjaan atau screenshot bukti tugas sesuai instruksi yang diberikan oleh asesor.
                </p>
                <div class="text-xs text-gray-500 space-y-1">
                    <div>Dinilai Kompeten: <strong>{{ $peserta->tugas_kompeten_count }}</strong> tugas</div>
                    <div>Perlu Revisi: <strong>{{ $peserta->tugas_belum_kompeten_count }}</strong> tugas</div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t border-gray-100">
                <a href="{{ route('peserta.tugas.index') }}" class="block text-center w-full py-2 bg-blue-600 text-white rounded text-xs font-semibold hover:bg-blue-700">
                    Buka Halaman Tugas &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Pilihan Ganti Skema -->
    <div class="bg-white border border-gray-200 rounded-lg p-5">
        <h3 class="text-sm font-bold text-gray-800 mb-1">Ganti Pilihan Skema Sertifikasi</h3>
        <p class="text-xs text-gray-500 mb-3">Klik skema untuk beralih ke skema sertifikasi yang lain.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($skemas as $s)
                @php $isAktif = ($peserta->skema_id == $s->id); @endphp
                <div class="p-3.5 rounded border text-xs flex flex-col justify-between {{ $isAktif ? 'bg-blue-50 border-blue-300' : 'bg-gray-50 border-gray-200' }}">
                    <div>
                        <span class="font-mono font-bold text-blue-700">{{ $s->kode_skema }}</span>
                        <div class="font-bold text-gray-900 mt-0.5">{{ $s->nama_skema }}</div>
                        <p class="text-gray-500 mt-1 line-clamp-2">{{ $s->deskripsi ?? '-' }}</p>
                    </div>

                    <div class="mt-3 pt-2">
                        @if($isAktif)
                            <span class="text-emerald-700 font-semibold block text-center">✓ Skema Aktif</span>
                        @else
                            <form action="{{ route('peserta.pilih.skema') }}" method="POST" onsubmit="return confirm('Pindah ke skema {{ $s->nama_skema }}?')">
                                @csrf
                                <input type="hidden" name="skema_id" value="{{ $s->id }}">
                                <button type="submit" class="w-full py-1.5 bg-gray-800 hover:bg-gray-900 text-white rounded font-medium">
                                    Pilih Skema
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
