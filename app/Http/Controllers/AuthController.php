<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('home');
        }

        return back()->withErrors([
            'username' => 'Kredensial tidak valid.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }


    public function register(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('auth.register');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'whatsapp' => ['required', 'string', 'regex:/^[0-9]{5,20}$/'],
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Format nomor WhatsApp ke format internasional
        $whatsapp = $this->formatWhatsApp($validated['whatsapp']);

        User::create([
            'name'     => $validated['name'],
            'username' => $validated['username'],
            'whatsapp' => $whatsapp,
            'password' => Hash::make($validated['password']),
        ]);

        return redirect('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }

    private function formatWhatsApp(string $number): string
    {
        // Buang semua karakter selain angka
        $number = preg_replace('/[^0-9]/', '', $number);

        // Jika dimulai dengan "0", ganti dengan "62"
        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        }

        // Jika belum dimulai dengan "62", tambahkan prefix "62"
        if (!str_starts_with($number, '62')) {
            $number = '62' . $number;
        }

        // Tambahkan "+" di depan
        return '+' . $number;
    }
}