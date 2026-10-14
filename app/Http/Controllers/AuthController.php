<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Peserta;
use App\Models\Skema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('auth.login');
    }

    // Menampilkan halaman pendaftaran
    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        $skemas = Skema::all();

        return view('auth.register', compact('skemas'));
    }

    // Memproses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->email === 'admin@gmail.com' && $user->role !== 'admin') {
                User::where('id', $user->id)->update(['role' => 'admin']);
            }

            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // Memproses pendaftaran akun & pembuatan data peserta langsung
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => [
                'required',
                'string',
                'max:50',
                "regex:/^[\pL\s\'`\"]+$/u",
            ],
            'nik' => [
                'required',
                'digits:16',
                'unique:pesertas,nik',
            ],
            'no_hp' => [
                'required',
                'regex:/^[0-9+]+$/',
                'max:15',
            ],
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string|max:1000',
            'skema_id' => 'required|exists:skemas,id',
            'email' => 'required|email|max:255|unique:users,email|unique:pesertas,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.max' => 'Nama lengkap maksimal 50 karakter.',
            'name.regex' => 'Nama tidak boleh mengandung angka. Hanya boleh huruf, spasi, dan tanda kutip.',
            'nik.digits' => 'NIK harus tepat 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar sebelumnya.',
            'no_hp.max' => 'Nomor Handphone maksimal 15 karakter.',
            'no_hp.regex' => 'Nomor Handphone hanya boleh berisi angka.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'skema_id.required' => 'Pilih skema sertifikasi yang ingin ditempuh.',
            'skema_id.exists' => 'Skema sertifikasi yang dipilih tidak valid.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = null;

        DB::transaction(function () use ($validatedData, &$user) {
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'no_hp' => $validatedData['no_hp'],
                'password' => Hash::make($validatedData['password']),
                'role' => 'peserta',
            ]);

            Peserta::create([
                'user_id' => $user->id,
                'nama' => $validatedData['name'],
                'nik' => $validatedData['nik'],
                'tempat_lahir' => $validatedData['tempat_lahir'],
                'tanggal_lahir' => $validatedData['tanggal_lahir'],
                'jenis_kelamin' => $validatedData['jenis_kelamin'],
                'email' => $validatedData['email'],
                'no_hp' => $validatedData['no_hp'],
                'alamat' => $validatedData['alamat'],
                'skema_id' => $validatedData['skema_id'],
                'tanggal_daftar' => now()->toDateString(),
                'status_kompetensi' => 'Belum Mengumpulkan',
            ]);
        });

        return redirect()
            ->route('login')
            ->with('success', 'Akun pendaftaran berhasil dibuat! Silakan masuk dengan email dan password Anda.');
    }

    // Memproses logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('welcome')->with('success', 'Anda telah berhasil keluar dari sistem LSP-APP.');
    }
}
