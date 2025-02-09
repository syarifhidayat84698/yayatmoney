<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Menampilkan form registrasi
    public function showRegistrationForm()
    {
        return view('auth.register'); // Mengarahkan ke view registrasi
    }

    // Menangani proses registrasi
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Ambil ID role Karyawan
        $karyawanRole = Role::where('name', 'karyawan')->first();

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Enkripsi password
            'role_id' => $karyawanRole->id, // Menyimpan role_id Karyawan
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login'); // Mengarahkan ke view login
    }

    // Menangani proses login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->intended('/'); // Redirect ke halaman yang diinginkan
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // Menangani proses logout
    public function logout(Request $request)
    {
        auth()->logout();
        return redirect('/login')->with('success', 'Anda telah logout.');
    }
}