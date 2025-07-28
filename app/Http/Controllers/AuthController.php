<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Daftar user hardcoded (gantikan dengan data yang Anda inginkan)
    private $users = [
        [
            'email' => 'admin@example.com',
            'password' => 'password123',
            'name' => 'Admin'
        ],
        [
            'email' => 'user@example.com',
            'password' => 'user1234',
            'name' => 'Regular User'
        ]
    ];

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Cek kecocokan email dan password
        $authenticatedUser = null;
        foreach ($this->users as $user) {
            if ($user['email'] === $credentials['email'] && 
                $user['password'] === $credentials['password']) {
                $authenticatedUser = $user;
                break;
            }
        }

        if ($authenticatedUser) {
            // Simpan user ke session
            $request->session()->put('user', $authenticatedUser);
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}