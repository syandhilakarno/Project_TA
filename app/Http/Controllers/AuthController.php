<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect('/admin');
                case 'koordinator':
                    return redirect('/koordinator');
                case 'dosen':
                    return redirect('/dosen');
                case 'mahasiswa':
                    return redirect('/mahasiswa');
            }
        }

        return back()->withErrors(['msg' => 'Email atau password salah']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
