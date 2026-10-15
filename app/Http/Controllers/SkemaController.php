<?php

namespace App\Http\Controllers;

use App\Models\Skema;
use Illuminate\Http\Request;

class SkemaController extends Controller
{
    /**
     * Nama Fungsi: index
     * Tujuan: Menampilkan daftar seluruh master skema sertifikasi beserta jumlah asesi dan jumlah tugas terkait.
     * Alur: Memanggil model Skema dengan eager loading count pesertas & tugas, lalu merender tampilan skemas/index.
     */
    public function index()
    {
        $skemas = Skema::withCount(['pesertas', 'tugas'])->latest()->get();

        return view('skemas.index', compact('skemas'));
    }

    /**
     * Nama Fungsi: create
     * Tujuan: Menampilkan halaman formulir pembuatan skema sertifikasi baru bagi Administrator.
     * Alur: Merender tampilan skemas/create.
     */
    public function create()
    {
        return view('skemas.create');
    }

    /**
     * Nama Fungsi: store
     * Tujuan: Memvalidasi dan menyimpan data skema sertifikasi baru ke dalam database tabel skemas.
     * Alur: Memvalidasi keunikan kode_skema dan nama_skema, membuat record baru, lalu redirect ke halaman index dengan pesan sukses.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_skema' => 'required|max:50|unique:skemas,kode_skema',
            'nama_skema' => 'required|max:255',
            'deskripsi' => 'nullable|string',
        ], [
            'kode_skema.required' => 'Kode skema sertifikasi wajib diisi.',
            'kode_skema.unique' => 'Kode skema ini sudah terdaftar.',
            'nama_skema.required' => 'Nama skema sertifikasi wajib diisi.',
        ]);

        Skema::create($validated);

        return redirect()
            ->route('skemas.index')
            ->with('success', 'Skema sertifikasi berhasil ditambahkan.');
    }

    /**
     * Nama Fungsi: edit
     * Tujuan: Menampilkan formulir untuk mengedit data skema sertifikasi yang dipilih.
     * Alur: Menerima objek Skema via route model binding, lalu merender tampilan skemas/edit.
     */
    public function edit(Skema $skema)
    {
        return view('skemas.edit', compact('skema'));
    }

    /**
     * Nama Fungsi: update
     * Tujuan: Memperbarui data skema sertifikasi yang ada di database berdasarkan input yang telah divalidasi.
     * Alur: Memvalidasi input (mengabaikan kode_skema milik skema saat ini), menjalankan method update, dan redirect kembali ke index.
     */
    public function update(Request $request, Skema $skema)
    {
        $validated = $request->validate([
            'kode_skema' => 'required|max:50|unique:skemas,kode_skema,' . $skema->id,
            'nama_skema' => 'required|max:255',
            'deskripsi' => 'nullable|string',
        ], [
            'kode_skema.required' => 'Kode skema sertifikasi wajib diisi.',
            'kode_skema.unique' => 'Kode skema ini sudah terdaftar untuk skema lain.',
            'nama_skema.required' => 'Nama skema sertifikasi wajib diisi.',
        ]);

        $skema->update($validated);

        return redirect()
            ->route('skemas.index')
            ->with('success', 'Skema sertifikasi berhasil diperbarui.');
    }

    /**
     * Nama Fungsi: destroy
     * Tujuan: Menghapus data skema sertifikasi dari database beserta relasi terkaitnya (cascade delete).
     * Alur: Menerima objek Skema, memanggil delete(), lalu mengembalikan ke daftar skema dengan pesan konfirmasi.
     */
    public function destroy(Skema $skema)
    {
        $namaSkema = $skema->nama_skema;
        $skema->delete();

        return redirect()
            ->route('skemas.index')
            ->with('success', "Skema '{$namaSkema}' berhasil dihapus.");
    }

    /**
     * Nama Fungsi: show
     * Tujuan: Menampilkan informasi detail skema sertifikasi serta daftar tugas uji yang ada pada skema tersebut.
     * Alur: Memuat relasi pesertas dan tugas dari model Skema, lalu merender tampilan skemas/show.
     */
    public function show(Skema $skema)
    {
        $skema->load(['pesertas', 'tugas']);
        return view('skemas.show', compact('skema'));
    }
}
