<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Skema;
use Illuminate\Http\Request;

class PesertaController extends Controller
{
    /**
     * Nama Fungsi: index
     * Tujuan: Menampilkan daftar seluruh peserta asesmen dengan fitur pencarian dan penyaringan data.
     * Alur: Memeriksa parameter pencarian (nama, NIK, email, no_hp) dan filter skema_id/status, menjalankan query Eloquent ke tabel pesertas dengan relasi skema, lalu merender tampilan pesertas/index.
     */
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

    /**
     * Nama Fungsi: create
     * Tujuan: Menampilkan formulir pendaftaran peserta asesmen baru oleh Administrator.
     * Alur: Mengambil daftar skema sertifikasi untuk pilihan dropdown, lalu merender tampilan pesertas/create.
     */
    public function create()
    {
        $skemas = Skema::all();

        return view('pesertas.create', compact('skemas'));
    }

    /**
     * Nama Fungsi: store
     * Tujuan: Memvalidasi dan menyimpan data peserta sertifikasi baru yang didaftarkan langsung oleh Administrator.
     * Alur: Memvalidasi format NIK 16 digit, format nama tanpa angka, nomor HP, email, dan skema_id, menetapkan status awal 'Belum Mengumpulkan', lalu menyimpannya ke tabel pesertas.
     */
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

    /**
     * Nama Fungsi: show
     * Tujuan: Menampilkan rincian profil biodata lengkap dan rekam jejak tugas unjuk kerja peserta.
     * Alur: Memuat relasi skema dan riwayat pengumpulanTugas peserta, lalu merender tampilan pesertas/show.
     */
    public function show(Peserta $peserta)
    {
        $peserta->load(['skema', 'pengumpulanTugas.tugas']);

        return view('pesertas.show', compact('peserta'));
    }

    /**
     * Nama Fungsi: edit
     * Tujuan: Menampilkan formulir perubahan data peserta sertifikasi yang dipilih oleh Administrator.
     * Alur: Mengambil data referensi skema sertifikasi dan data peserta, lalu merender tampilan pesertas/edit.
     */
    public function edit(Peserta $peserta)
    {
        $skemas = Skema::all();

        return view('pesertas.edit', compact('peserta', 'skemas'));
    }

    /**
     * Nama Fungsi: update
     * Tujuan: Memperbarui data biodata peserta di database sekaligus menyinkronkan data akun User yang terhubung jika ada.
     * Alur: Memvalidasi input data dengan mengabaikan NIK peserta saat ini, menyimpan perubahan ke tabel pesertas, dan mengupdate nama/no_hp di tabel users jika memiliki akun login.
     */
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

    /**
     * Nama Fungsi: destroy
     * Tujuan: Menghapus catatan data peserta dari database.
     * Alur: Memanggil method delete() pada model Peserta dan redirect kembali ke pesertas/index dengan flash message.
     */
    public function destroy(Peserta $peserta)
    {
        $nama = $peserta->nama;
        $peserta->delete();

        return redirect()
            ->route('pesertas.index')
            ->with('success', "Data peserta '{$nama}' berhasil dihapus.");
    }
}
