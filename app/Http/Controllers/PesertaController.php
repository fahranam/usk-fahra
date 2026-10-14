<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Skema;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    // Menampilkan daftar seluruh peserta sertifikasi dengan fitur pencarian dan filter skema
    public function index(Request $request)
    {
        $query = Peserta::with('skema');

        if ($request->filled('search')) {
            $search = (string) $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_hp', 'like', "%{$search}%");
            });
        }

        if ($request->filled('skema_id')) {
            $query->where('skema_id', $request->skema_id);
        }

        if ($request->filled('status')) {
            $query->where('status_kompetensi', $request->status);
        }

        $pesertas = $query->latest()->get();
        $skemas = Skema::all();

        return view('pesertas.index', compact('pesertas', 'skemas'));
    }

    // Menampilkan halaman formulir tambah peserta baru oleh Admin
    public function create()
    {
        $skemas = Skema::all();

        return view('pesertas.create', compact('skemas'));
    }

    // Menyimpan data peserta baru ke database dengan validasi
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:50',
                "regex:/^[\pL\s\'`\"]+$/u",
            ],
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|digits:16|unique:pesertas,nik',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|regex:/^[0-9+]+$/|max:15',
            'alamat' => 'required|string',
            'skema_id' => 'required|exists:skemas,id',
            'tanggal_daftar' => 'required|date',
        ], [
            'nama.max' => 'Nama lengkap maksimal 50 karakter.',
            'nama.regex' => 'Nama tidak boleh mengandung angka. Hanya boleh huruf, spasi, dan tanda kutip.',
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar sebelumnya.',
            'no_hp.max' => 'Nomor Handphone maksimal 15 karakter.',
            'no_hp.regex' => 'Nomor Handphone hanya boleh berisi angka.',
            'skema_id.required' => 'Pilih skema sertifikasi yang diikuti.',
        ]);

        $validated['status_kompetensi'] = 'Belum Mengumpulkan';

        Peserta::create($validated);

        return redirect()
            ->route('pesertas.index')
            ->with('success', 'Data peserta sertifikasi berhasil ditambahkan.');
    }

    // Menampilkan detail lengkap satu peserta sertifikasi
    public function show(Peserta $peserta)
    {
        $peserta->load(['skema', 'pengumpulanTugas.tugas']);

        return view('pesertas.show', compact('peserta'));
    }

    // Menampilkan formulir edit data peserta
    public function edit(Peserta $peserta)
    {
        $skemas = Skema::all();

        return view('pesertas.edit', compact('peserta', 'skemas'));
    }

    // Memproses dan menyimpan pembaruan data peserta
    public function update(Request $request, Peserta $peserta)
    {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:50',
                "regex:/^[\pL\s\'`\"]+$/u",
            ],
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => "required|digits:16|unique:pesertas,nik,{$peserta->id}",
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'email' => 'required|email|max:255',
            'no_hp' => 'required|regex:/^[0-9+]+$/|max:15',
            'alamat' => 'required|string',
            'skema_id' => 'required|exists:skemas,id',
            'tanggal_daftar' => 'required|date',
        ], [
            'nama.max' => 'Nama lengkap maksimal 50 karakter.',
            'nama.regex' => 'Nama tidak boleh mengandung angka. Hanya boleh huruf, spasi, dan tanda kutip.',
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'no_hp.max' => 'Nomor Handphone maksimal 15 karakter.',
            'no_hp.regex' => 'Nomor Handphone hanya boleh berisi angka.',
        ]);

        $peserta->update($validated);

        // Jika peserta punya akun user terkait, sinkronkan juga
        if ($peserta->user_id) {
            \App\Models\User::where('id', $peserta->user_id)->update([
                'name' => $validated['nama'],
                'no_hp' => $validated['no_hp'],
            ]);
        }

        return redirect()
            ->route('pesertas.index')
            ->with('success', 'Data peserta berhasil diperbarui.');
    }

    // Menghapus data peserta dari database
    public function destroy(Peserta $peserta)
    {
        $nama = $peserta->nama;
        $peserta->delete();

        return redirect()
            ->route('pesertas.index')
            ->with('success', "Data peserta '{$nama}' berhasil dihapus.");
    }
}
