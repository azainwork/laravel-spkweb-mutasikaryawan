<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->only('nip', 'password');
        if (Auth::attempt(['nip' => $credentials['nip'], 'password' => $credentials['password']])) {
            $user = Auth::user();
            if ($user->role->name == 'admin') return redirect()->route('admin.dashboard');
            if ($user->role->name == 'pegawai') return redirect()->route('pegawai.dashboard');
            if ($user->role->name == 'kepala_pusat') return redirect()->route('kepalapusat.dashboard');
        }
        return back()->withErrors(['nip' => 'NIP atau password salah']);
    }

    public function logout() {
        Auth::logout();
        return redirect()->route('login');
    }
}
