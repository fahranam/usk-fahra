<?php

namespace App\Http\Controllers;

use App\Models\Peserta;
use App\Models\Skema;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PesertaProfileController extends Controller
{
    // Menampilkan dashboard peserta
    public function dashboard()
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->email === 'admin@gmail.com') {
            return redirect()->route('dashboard');
        }

        $peserta = $user->peserta;

        if (!$peserta) {
            return redirect()->route('peserta.profil.create');
        }

        $skemas = Skema::all();

        return view('pesertas.dashboard', compact('peserta', 'skemas'));
    }

    // Mengganti / memilih skema sertifikasi baru dari dashboard peserta
    public function pilihSkemaBaru(Request $request)
    {
        $user = Auth::user();
        $peserta = $user->peserta;

        if (!$peserta) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'skema_id' => 'required|exists:skemas,id',
        ], [
            'skema_id.required' => 'Pilih skema sertifikasi yang ingin ditempuh.',
            'skema_id.exists' => 'Skema sertifikasi yang dipilih tidak valid.',
        ]);

        if ($peserta->skema_id != $validated['skema_id']) {
            $peserta->skema_id = $validated['skema_id'];
            $peserta->status_kompetensi = 'Belum Mengumpulkan';
            $peserta->save();

            $skemaBaru = Skema::find($validated['skema_id']);

            return redirect()
                ->route('dashboard')
                ->with('success', 'Skema sertifikasi berhasil diperbarui ke: ' . $skemaBaru->nama_skema);
        }

        return redirect()
            ->route('dashboard')
            ->with('success', 'Skema sertifikasi yang dipilih saat ini sudah aktif.');
    }

    // Menampilkan formulir pelengkapan profil jika belum ada
    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->email === 'admin@gmail.com') {
            return redirect()->route('dashboard');
        }

        if ($user->peserta) {
            return redirect()->route('dashboard');
        }

        $skemas = Skema::all();

        return view('pesertas.profil', compact('user', 'skemas'));
    }

    // Menyimpan profil peserta (pertama kali setelah register)
    public function store(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'admin' || $user->email === 'admin@gmail.com') {
            return redirect()->route('dashboard');
        }

        if ($user->peserta) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'nik' => 'required|digits:16|unique:pesertas,nik',
            'alamat' => 'required|string|max:1000',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'skema_id' => 'required|exists:skemas,id',
        ], [
            'nik.digits' => 'NIK harus tepat 16 digit.',
            'nik.unique' => 'NIK ini sudah terdaftar.',
            'skema_id.required' => 'Pilih skema sertifikasi.',
        ]);

        Peserta::create([
            'user_id' => $user->id,
            'nama' => $user->name,
            'nik' => $validated['nik'],
            'email' => $user->email,
            'no_hp' => $user->no_hp ?? null,
            'tempat_lahir' => $validated['tempat_lahir'] ?? null,
            'tanggal_lahir' => $validated['tanggal_lahir'] ?? null,
            'alamat' => $validated['alamat'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'skema_id' => $validated['skema_id'],
            'tanggal_daftar' => now()->toDateString(),
            'status_kompetensi' => 'Belum Mengumpulkan',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Profil peserta berhasil dilengkapi! Selamat datang di LSP-APP.');
    }

    // Menampilkan form edit profil & ganti password untuk Admin maupun Peserta
    public function edit()
    {
        $user = Auth::user();
        $peserta = null;
        $skemas = [];

        if ($user->role !== 'admin') {
            $peserta = Peserta::where('user_id', $user->id)->first();

            if (!$peserta) {
                return redirect()->route('peserta.profil.create');
            }

            $skemas = Skema::all();
        }

        return view('pesertas.edit-profile', compact('user', 'peserta', 'skemas'));
    }

    // Menyimpan perubahan data profil
    public function update(Request $request)
    {
        $user = Auth::user();

        // Jika Admin, perbarui data akun user
        if ($user->role === 'admin') {
            $validated = $request->validate([
                'nama' => 'required|string|max:50',
                'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            ]);

            User::where('id', $user->id)->update([
                'name' => $validated['nama'],
                'email' => $validated['email'],
            ]);

            return back()->with('success', 'Data akun admin berhasil diperbarui.');
        }

        // Jika Peserta, perbarui data peserta & user
        $peserta = Peserta::where('user_id', $user->id)->first();

        if (!$peserta) {
            return redirect()->route('peserta.profil.create');
        }

        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:50',
                "regex:/^[\pL\s\'`\"]+$/u",
            ],
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nik' => 'required|digits:16|unique:pesertas,nik,' . $peserta->id,
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id . '|unique:pesertas,email,' . $peserta->id,
            'no_hp' => 'required|regex:/^[0-9+]+$/|max:15',
            'alamat' => 'required|string|max:1000',
            'skema_id' => 'required|exists:skemas,id',
        ], [
            'nama.max' => 'Nama lengkap maksimal 50 karakter.',
            'nama.regex' => 'Nama tidak boleh mengandung angka. Hanya boleh huruf, spasi, dan tanda kutip.',
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'no_hp.max' => 'Nomor Handphone maksimal 15 karakter.',
            'no_hp.regex' => 'Nomor Handphone hanya boleh berisi angka.',
        ]);

        $peserta->nama = $validated['nama'];
        $peserta->nik = $validated['nik'];
        $peserta->tempat_lahir = $validated['tempat_lahir'];
        $peserta->tanggal_lahir = $validated['tanggal_lahir'];
        $peserta->email = $validated['email'];
        $peserta->no_hp = $validated['no_hp'] ?? null;
        $peserta->alamat = $validated['alamat'];
        $peserta->jenis_kelamin = $validated['jenis_kelamin'];
        $peserta->skema_id = $validated['skema_id'];
        $peserta->save();

        User::where('id', $user->id)->update([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
        ]);

        return back()->with('success', 'Biodata profil berhasil diperbarui.');
    }

    // Memproses penggantian password akun (berlaku untuk Admin & Peserta)
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        User::where('id', $user->id)->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password berhasil diperbarui! Silakan gunakan password baru ini.');
    }
}
