@extends('layouts.admin')

@section('title', 'Data Peserta')
@section('page-title', 'Data Peserta Asesi')

@section('content')
<div class="space-y-4">

    <!-- Header & Search -->
    <div class="bg-white p-4 rounded-lg border border-gray-200 flex flex-col sm:flex-row gap-3 items-center justify-between">
        <form action="{{ route('pesertas.index') }}" method="GET" class="flex-1 flex gap-2 w-full sm:w-auto">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, NIK, email..."
                   class="px-3 py-1.5 border border-gray-300 rounded text-xs flex-1 sm:max-w-xs focus:outline-none focus:border-blue-600">

            <select name="skema_id" class="px-2 py-1.5 border border-gray-300 rounded text-xs bg-white focus:outline-none focus:border-blue-600">
                <option value="">Semua Skema</option>
                @foreach($skemas as $sk)
                    <option value="{{ $sk->id }}" @selected(request('skema_id') == $sk->id)>{{ $sk->kode_skema }}</option>
                @endforeach
            </select>

            <button type="submit" class="px-3 py-1.5 bg-gray-800 text-white rounded text-xs font-medium hover:bg-gray-900">
                Cari
            </button>
            @if(request()->hasAny(['search', 'skema_id']))
                <a href="{{ route('pesertas.index') }}" class="px-2 py-1.5 text-gray-500 hover:text-gray-800 text-xs">Reset</a>
            @endif
        </form>

        <a href="{{ route('pesertas.create') }}" class="px-3.5 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-medium self-end sm:self-auto">
            + Tambah Peserta
        </a>
    </div>

    <!-- Table -->
    <div class="bg-white border border-gray-200 rounded-lg overflow-x-auto">
        <table class="w-full text-left text-xs">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600 font-semibold uppercase tracking-wider">
                <tr>
                    <th class="p-3 w-10 text-center">No</th>
                    <th class="p-3">Nama Lengkap</th>
                    <th class="p-3">NIK</th>
                    <th class="p-3">JK</th>
                    <th class="p-3">Skema</th>
                    <th class="p-3">Status</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($pesertas as $peserta)
                    <tr class="hover:bg-gray-50">
                        <td class="p-3 text-center text-gray-400">{{ $loop->iteration }}</td>
                        <td class="p-3">
                            <div class="font-bold text-gray-900">{{ $peserta->nama }}</div>
                            <div class="text-[11px] text-gray-400">{{ $peserta->email }} &bull; {{ $peserta->no_hp ?? '-' }}</div>
                        </td>
                        <td class="p-3 font-mono text-gray-600">{{ $peserta->nik }}</td>
                        <td class="p-3 text-gray-600">{{ $peserta->jenis_kelamin }}</td>
                        <td class="p-3">
                            <span class="font-mono text-blue-700 font-semibold">{{ $peserta->skema->kode_skema ?? '-' }}</span>
                        </td>
                        <td class="p-3">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold
                                @if($peserta->status_kompetensi === 'Kompeten') bg-emerald-50 text-emerald-700 border border-emerald-200
                                @elseif($peserta->status_kompetensi === 'Belum Kompeten') bg-rose-50 text-rose-700 border border-rose-200
                                @elseif($peserta->status_kompetensi === 'Menunggu Penilaian') bg-amber-50 text-amber-700 border border-amber-200
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ $peserta->status_kompetensi }}
                            </span>
                        </td>
                        <td class="p-3 text-right whitespace-nowrap">
                            <a href="{{ route('pesertas.show', $peserta->id) }}" class="text-blue-600 hover:underline font-medium mr-2">Detail</a>
                            <a href="{{ route('pesertas.edit', $peserta->id) }}" class="text-gray-600 hover:underline font-medium mr-2">Edit</a>
                            <form action="{{ route('pesertas.destroy', $peserta->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus {{ $peserta->nama }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 hover:underline font-medium">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-400">Tidak ada data peserta ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
