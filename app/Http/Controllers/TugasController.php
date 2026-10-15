<?php

namespace App\Http\Controllers;

use App\Models\Skema;
use App\Models\Tugas;
use App\Models\Peserta;
use App\Models\PengumpulanTugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TugasController extends Controller
{
    /* ========================================================
       AREA ADMINISTRATOR: Kelola Tugas Per Skema & Penilaian
       ======================================================== */

    /**
     * Nama Fungsi: indexAdmin
     * Tujuan: Menampilkan daftar seluruh tugas uji kompetensi yang dikelompokkan berdasarkan masing-masing skema sertifikasi.
     * Alur: Memuat data skema beserta relasi tugas, pengumpulan, dan asesi terdaftar, lalu merender tampilan tugas/admin-index.
     */
    public function indexAdmin()
    {
        $skemas = Skema::with(['tugas.pengumpulan', 'pesertas'])->get();

        return view('tugas.admin-index', compact('skemas'));
    }

    /**
     * Nama Fungsi: createAdmin
     * Tujuan: Menampilkan formulir pembuatan tugas uji kompetensi baru untuk skema tertentu.
     * Alur: Mengambil daftar semua skema sertifikasi dan parameter query skema_id opsional, lalu merender tampilan tugas/create.
     */
    public function createAdmin(Request $request)
    {
        $skemas = Skema::all();
        $selectedSkemaId = $request->query('skema_id');

        return view('tugas.create', compact('skemas', 'selectedSkemaId'));
    }

    /**
     * Nama Fungsi: storeAdmin
     * Tujuan: Menyimpan tugas unjuk kerja baru ke database tabel tugas yang otomatis berlaku bagi seluruh asesi di skema tersebut.
     * Alur: Memvalidasi skema_id, judul tugas, dan deskripsi instruksi tugas, lalu membuat record baru pada model Tugas.
     */
    public function storeAdmin(Request $request)
    {
        $validated = $request->validate([
            'skema_id' => 'required|exists:skemas,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ], [
            'skema_id.required' => 'Pilih skema sertifikasi target tugas.',
            'judul.required' => 'Judul tugas wajib diisi.',
            'deskripsi.required' => 'Deskripsi dan petunjuk tugas wajib diisi.',
        ]);

        Tugas::create($validated);

        return redirect()
            ->route('admin.tugas.index')
            ->with('success', 'Tugas berhasil dibuat dan otomatis diterbitkan untuk semua peserta pada skema tersebut.');
    }

    /**
     * Nama Fungsi: editAdmin
     * Tujuan: Menampilkan formulir pengubahan data dan petunjuk tugas skema yang sudah ada.
     * Alur: Menerima objek model Tugas via route binding dan memuat daftar skema, lalu merender tampilan tugas/edit.
     */
    public function editAdmin(Tugas $tugas)
    {
        $skemas = Skema::all();

        return view('tugas.edit', compact('tugas', 'skemas'));
    }

    /**
     * Nama Fungsi: updateAdmin
     * Tujuan: Memperbarui judul, petunjuk, atau penugasan skema pada tugas yang ada di database.
     * Alur: Memvalidasi data input baru, menjalankan update pada model Tugas, dan redirect ke daftar tugas admin.
     */
    public function updateAdmin(Request $request, Tugas $tugas)
    {
        $validated = $request->validate([
            'skema_id' => 'required|exists:skemas,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ], [
            'skema_id.required' => 'Pilih skema sertifikasi target tugas.',
            'judul.required' => 'Judul tugas wajib diisi.',
            'deskripsi.required' => 'Deskripsi dan petunjuk tugas wajib diisi.',
        ]);

        $tugas->update($validated);

        return redirect()
            ->route('admin.tugas.index')
            ->with('success', 'Tugas skema berhasil diperbarui.');
    }

    /**
     * Nama Fungsi: destroyAdmin
     * Tujuan: Menghapus data tugas dari database sekaligus membersihkan berkas file bukti gambar yang pernah diunggah oleh asesi.
     * Alur: Mengiterasi pengumpulan tugas terkait untuk menghapus file fisik di storage/public dengan unlink, lalu menghapus record Tugas.
     */
    public function destroyAdmin(Tugas $tugas)
    {
        foreach ($tugas->pengumpulan as $p) {
            if ($p->file_jawaban && file_exists(public_path($p->file_jawaban))) {
                @unlink(public_path($p->file_jawaban));
            }
        }

        $tugas->delete();

        return redirect()
            ->route('admin.tugas.index')
            ->with('success', 'Tugas skema berhasil dihapus.');
    }

    /**
     * Nama Fungsi: penilaianAdmin
     * Tujuan: Menampilkan daftar seluruh asesi pada suatu skema beserta status pengumpulan jawaban pada tugas yang dipilih.
     * Alur: Mengambil data asesi yang terdaftar di skema tugas tersebut beserta relasi pengumpulanTugas-nya, lalu merender tampilan tugas/admin-penilaian.
     */
    public function penilaianAdmin(Tugas $tugas)
    {
        $tugas->load('skema');
        $pesertas = Peserta::where('skema_id', $tugas->skema_id)
            ->with(['pengumpulanTugas' => function ($q) use ($tugas) {
                $q->where('tugas_id', $tugas->id);
            }])
            ->get();

        return view('tugas.admin-penilaian', compact('tugas', 'pesertas'));
    }

    /**
     * Nama Fungsi: reviewPesertaAdmin
     * Tujuan: Menampilkan lembar kerja pemeriksaan bukti jawaban (link/gambar) satu orang asesi tertentu beserta form keputusan penilaian asesor.
     * Alur: Mengambil record pengumpulan_tugas berdasarkan tugas_id dan peserta_id, lalu merender tampilan tugas/admin-review-peserta.
     */
    public function reviewPesertaAdmin(Tugas $tugas, Peserta $peserta)
    {
        $tugas->load('skema');
        $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('peserta_id', $peserta->id)
            ->first();

        return view('tugas.admin-review-peserta', compact('tugas', 'peserta', 'pengumpulan'));
    }

    /**
     * Nama Fungsi: simpanNilaiAdmin
     * Tujuan: Menyimpan keputusan penilaian asesor ('Kompeten' atau 'Belum Kompeten') dan catatan feedback, lalu memperbarui status kelulusan akhir asesi.
     * Alur: Memperbarui record pengumpulan_tugas via updateOrCreate, memanggil method $peserta->sinkronkanStatusKompetensi() untuk evaluasi bertahap, lalu redirect kembali ke daftar penilaian.
     */
    public function simpanNilaiAdmin(Request $request, Tugas $tugas, Peserta $peserta)
    {
        $validated = $request->validate([
            'status' => 'required|in:Kompeten,Belum Kompeten',
            'catatan_admin' => 'nullable|string|max:1000',
        ]);

        PengumpulanTugas::updateOrCreate(
            [
                'tugas_id' => $tugas->id,
                'peserta_id' => $peserta->id,
            ],
            [
                'status' => $validated['status'],
                'catatan_admin' => $validated['catatan_admin'],
            ]
        );

        $statusKeseluruhan = $peserta->sinkronkanStatusKompetensi();

        if ($statusKeseluruhan === 'Kompeten') {
            $pesan = "Penilaian disimpan! Selamat, seluruh tugas ({$peserta->tugas_kompeten_count} dari {$peserta->total_tugas}) selesai dan {$peserta->nama} resmi dinyatakan KOMPETEN (LULUS).";
        } elseif ($validated['status'] === 'Kompeten') {
            $pesan = "Penilaian tugas ini disimpan sebagai Kompeten ({$peserta->tugas_kompeten_count} dari {$peserta->total_tugas} tugas selesai).";
        } else {
            $pesan = "Penilaian tugas disimpan sebagai BELUM KOMPETEN (perlu revisi dari peserta).";
        }

        return redirect()
            ->route('admin.tugas.penilaian', $tugas->id)
            ->with('success', $pesan);
    }


    /* ========================================================
       AREA ASESI / PESERTA: Melihat & Mengumpulkan Bukti Tugas
       ======================================================== */

    /**
     * Nama Fungsi: indexPeserta
     * Tujuan: Menampilkan daftar seluruh tugas unjuk kerja yang wajib dikerjakan oleh asesi sesuai skema sertifikasi yang sedang aktif.
     * Alur: Mengidentifikasi asesi yang sedang login, memuat seluruh tugas pada skema_id asesi beserta pengumpulan miliknya, lalu merender tampilan pesertas/tugas.
     */
    public function indexPeserta()
    {
        $user = Auth::user();
        $peserta = $user->peserta;

        if (!$peserta) {
            return redirect()->route('dashboard');
        }

        $daftarTugas = Tugas::where('skema_id', $peserta->skema_id)
            ->with(['pengumpulan' => function ($q) use ($peserta) {
                $q->where('peserta_id', $peserta->id);
            }])
            ->latest()
            ->get();

        return view('pesertas.tugas', compact('peserta', 'daftarTugas'));
    }

    /**
     * Nama Fungsi: submitPeserta
     * Tujuan: Menerima, memvalidasi, mengunggah berkas portofolio (gambar/screenshot) dan tautan URL pengerjaan tugas dari asesi.
     * Alur: Memastikan tugas cocok dengan skema asesi, memvalidasi URL dan file gambar (maks 3MB), menyimpan file ke public/uploads/tugas, menyimpan ke tabel pengumpulan_tugas dengan status 'Menunggu Penilaian', dan menyinkronkan status kelulusan asesi.
     */
    public function submitPeserta(Request $request, Tugas $tugas)
    {
        $user = Auth::user();
        $peserta = $user->peserta;

        if (!$peserta || $tugas->skema_id !== $peserta->skema_id) {
            abort(403, 'Aksi tidak diizinkan. Tugas tidak sesuai skema sertifikasi Anda.');
        }

        $validated = $request->validate([
            'link_jawaban' => 'nullable|url|max:500',
            'gambar_jawaban' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ], [
            'link_jawaban.url' => 'Format tautan/URL tidak valid (harus diawali http:// atau https://).',
            'gambar_jawaban.image' => 'File harus berupa gambar.',
            'gambar_jawaban.mimes' => 'Format gambar yang didukung: JPG, PNG, WEBP.',
            'gambar_jawaban.max' => 'Ukuran gambar maksimal 3 MB.',
        ]);

        $pengumpulan = PengumpulanTugas::where('tugas_id', $tugas->id)
            ->where('peserta_id', $peserta->id)
            ->first();

        if (empty($validated['link_jawaban']) && !$request->hasFile('gambar_jawaban') && (!$pengumpulan || (!$pengumpulan->link_jawaban && !$pengumpulan->file_jawaban))) {
            return back()->withErrors([
                'link_jawaban' => 'Harap masukkan tautan (link) atau unggah gambar bukti pengerjaan tugas.',
            ]);
        }

        $filePath = $pengumpulan?->file_jawaban;

        // Upload gambar jika ada
        if ($request->hasFile('gambar_jawaban')) {
            if ($filePath && file_exists(public_path($filePath))) {
                @unlink(public_path($filePath));
            }

            $uploadDir = public_path('uploads/tugas');
            if (!file_exists($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }

            $file = $request->file('gambar_jawaban');
            $fileName = 'tugas_' . $tugas->id . '_peserta_' . $peserta->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $filePath = 'uploads/tugas/' . $fileName;
        }

        $linkJawaban = $request->filled('link_jawaban')
            ? $validated['link_jawaban']
            : ($pengumpulan?->link_jawaban ?? null);

        PengumpulanTugas::updateOrCreate(
            [
                'tugas_id' => $tugas->id,
                'peserta_id' => $peserta->id,
            ],
            [
                'link_jawaban' => $linkJawaban,
                'file_jawaban' => $filePath,
                'status' => 'Menunggu Penilaian',
            ]
        );

        $peserta->sinkronkanStatusKompetensi();

        return redirect()
            ->route('peserta.tugas.index')
            ->with('success', 'Tugas berhasil dikumpulkan (' . $peserta->tugas_selesai_count . ' dari ' . $peserta->total_tugas . ' tugas selesai dikerjakan). Menunggu penilaian Asesor.');
    }
}
