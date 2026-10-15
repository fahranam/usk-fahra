<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SkemaController;
use App\Http\Controllers\PesertaController;
use App\Http\Controllers\PesertaProfileController;
use App\Http\Controllers\TugasController;

/*
|--------------------------------------------------------------------------
| Web Routes - LSP-APP (Lembaga Sertifikasi Profesi)
|--------------------------------------------------------------------------
| Dokumentasi Rute: Setiap fungsi rute di bawah dilengkapi tujuan dan
| alur pemanggilannya dalam Bahasa Indonesia.
*/

// =========================================================================
// RUTE UMUM & AUTENTIKASI (Dapat diakses publik / belum login)
// =========================================================================

/**
 * Rute: GET /
 * Nama Rute: welcome
 * Tujuan Fungsi: Menampilkan halaman utama (landing page) publik LSP-APP yang memuat data ringkasan metrik sertifikasi dan katalog skema kompetensi.
 */
Route::get('/', function () {
    $skemas = \App\Models\Skema::withCount('pesertas')->get();
    $totalPeserta = \App\Models\Peserta::count();
    $totalSkema = $skemas->count();
    $totalKompeten = \App\Models\Peserta::where('status_kompetensi', 'Kompeten')->count();

    return view('welcome', compact('skemas', 'totalPeserta', 'totalSkema', 'totalKompeten'));
})->name('welcome');

/**
 * Rute Autentikasi Pengguna
 * Tujuan: Menangani alur login, registrasi asesi baru, dan logout akun.
 */
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');


// =========================================================================
// RUTE DASHBOARD UTAMA (Wajib Login)
// =========================================================================

/**
 * Rute: GET /dashboard
 * Nama Rute: dashboard
 * Tujuan Fungsi: Mengarahkan pengguna ke dashboard yang sesuai (Admin ke dashboard manajemen dengan ringkasan statistik, Peserta ke dashboard asesi).
 */
Route::get('/dashboard', function () {
    $user = \Illuminate\Support\Facades\Auth::user();

    // Memastikan akun admin default selalu memiliki role admin
    if ($user->email === 'admin@gmail.com' && $user->role !== 'admin') {
        \App\Models\User::where('id', $user->id)->update(['role' => 'admin']);
        $user->role = 'admin';
    }

    if ($user->role === 'admin' || $user->email === 'admin@gmail.com') {
        $totalPeserta = \App\Models\Peserta::count();
        $totalSkema = \App\Models\Skema::count();
        $totalTugas = \App\Models\Tugas::count();
        $totalKompeten = \App\Models\Peserta::where('status_kompetensi', 'Kompeten')->count();
        $pesertaTerbaru = \App\Models\Peserta::with('skema')->latest()->take(5)->get();
        $skemas = \App\Models\Skema::withCount('pesertas')->get();

        return view('dashboard', compact(
            'totalPeserta',
            'totalSkema',
            'totalTugas',
            'totalKompeten',
            'pesertaTerbaru',
            'skemas'
        ));
    }

    $peserta = \App\Models\Peserta::where('user_id', $user->id)->first();

    if (!$peserta) {
        return redirect()->route('peserta.profil.create');
    }

    $skemas = \App\Models\Skema::all();

    return view('pesertas.dashboard', compact('peserta', 'skemas'));
})->middleware('auth')->name('dashboard');


// =========================================================================
// RUTE KHUSUS PESERTA ASESI (Wajib Login)
// =========================================================================
Route::middleware('auth')->group(function () {
    // Menampilkan dashboard asesi
    Route::get('/dashboard-peserta', [PesertaProfileController::class, 'dashboard'])->name('dashboard.peserta');

    // Memproses pergantian skema sertifikasi asesi
    Route::post('/pilih-skema-baru', [PesertaProfileController::class, 'pilihSkemaBaru'])->name('peserta.pilih.skema');

    // Menampilkan dan menyimpan formulir pengisian data awal peserta
    Route::get('/profil-peserta/isi', [PesertaProfileController::class, 'create'])->name('peserta.profil.create');
    Route::post('/profil-peserta/isi', [PesertaProfileController::class, 'store'])->name('peserta.profil.store');

    // Menampilkan halaman pengaturan data diri serta ubah kata sandi
    Route::get('/data-peserta-saya', [PesertaProfileController::class, 'edit'])->name('peserta.profil.edit');
    Route::get('/profil', [PesertaProfileController::class, 'edit'])->name('profil.edit');
    Route::put('/data-peserta-saya', [PesertaProfileController::class, 'update'])->name('peserta.profil.update');
    Route::put('/ubah-password-peserta', [PesertaProfileController::class, 'updatePassword'])->name('peserta.password.update');
    Route::put('/ubah-password', [PesertaProfileController::class, 'updatePassword'])->name('password.update');

    // Menampilkan daftar tugas skema asesi dan formulir pengumpulan bukti tugas
    Route::get('/tugas-saya', [TugasController::class, 'indexPeserta'])->name('peserta.tugas.index');
    Route::post('/tugas-saya/{tugas}/kumpulkan', [TugasController::class, 'submitPeserta'])->name('peserta.tugas.submit');
});


// =========================================================================
// RUTE KHUSUS ADMINISTRATOR (Wajib Login & Role Admin)
// =========================================================================
Route::middleware(['auth', 'admin'])->group(function () {
    // CRUD Manajemen Master Skema Sertifikasi
    Route::resource('skemas', SkemaController::class);

    // CRUD Manajemen Master Data Peserta Asesi
    Route::resource('pesertas', PesertaController::class);

    // Manajemen Tugas & Penilaian Asesor
    Route::get('/admin/tugas', [TugasController::class, 'indexAdmin'])->name('admin.tugas.index');
    Route::get('/admin/tugas/tambah', [TugasController::class, 'createAdmin'])->name('admin.tugas.create');
    Route::post('/admin/tugas', [TugasController::class, 'storeAdmin'])->name('admin.tugas.store');
    Route::get('/admin/tugas/{tugas}/edit', [TugasController::class, 'editAdmin'])->name('admin.tugas.edit');
    Route::put('/admin/tugas/{tugas}', [TugasController::class, 'updateAdmin'])->name('admin.tugas.update');
    Route::delete('/admin/tugas/{tugas}', [TugasController::class, 'destroyAdmin'])->name('admin.tugas.destroy');

    // Penilaian Uji Kompetensi Asesi oleh Asesor
    Route::get('/admin/tugas/{tugas}/penilaian', [TugasController::class, 'penilaianAdmin'])->name('admin.tugas.penilaian');
    Route::get('/admin/tugas/{tugas}/peserta/{peserta}', [TugasController::class, 'reviewPesertaAdmin'])->name('admin.tugas.reviewPeserta');
    Route::post('/admin/tugas/{tugas}/peserta/{peserta}/nilai', [TugasController::class, 'simpanNilaiAdmin'])->name('admin.tugas.simpanNilai');
});
