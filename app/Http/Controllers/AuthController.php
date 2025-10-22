<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http; 
use Illuminate\Support\Str;
use App\Models\User;       // <-- Pastikan Anda import User
use App\Models\Mahasiswa;  // <-- Pastikan Anda import Mahasiswa
use Illuminate\Support\Facades\Hash; // <-- Pastikan Anda import Hash

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Menangani proses login menggunakan API Gateway (DENGAN DEV BYPASS).
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        // ========================================================
        // ========= MASTER DEV LOGIN BYPASS (ALL ROLES) ==========
        // ========================================================
        if ($password == 'password') {
            $lvl = 0;
            $level_name = 'guest';
            $loggedInUser = null;

            // 1. Cek dulu di tabel Mahasiswa (berdasarkan relasi user)
            //    Ini mencari user yang emailnya ada di tabel 'users' DAN
            //    punya relasi ke profil di tabel 'mahasiswa'
            $mahasiswa = Mahasiswa::whereHas('user', function ($query) use ($email) {
                $query->where('email', $email);
            })->with('user')->first();
            
            if ($mahasiswa) {
                $lvl = 2;
                $level_name = 'mahasiswa';
                $loggedInUser = $mahasiswa->user; // Ambil data dari tabel users
            }

            // 2. Jika bukan mahasiswa, cek di tabel User (Dosen, Koor, dll)
            if (!$loggedInUser) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    $loggedInUser = $user;
                    
                    // Tentukan level berdasarkan role di DB
                    switch ($user->role) {
                        case 'koordinator':
                            $lvl = 4;
                            $level_name = 'koordinator';
                            break;
                        case 'tata_usaha':
                            $lvl = 5;
                            $level_name = 'tata_usaha';
                            break;
                        case 'fakultas':
                            $lvl = 6;
                            $level_name = 'fakultas';
                            break;
                        case 'dosen':
                        default:
                            $lvl = 3; // Defaultnya Dosen
                            $level_name = 'dosen';
                    }
                }
            }

            // 3. Jika user (dari tabel manapun) ditemukan, buat session
            if ($loggedInUser) {
                // Login-kan user-nya secara manual ke Auth Laravel
                Auth::login($loggedInUser);
                
                $request->session()->regenerate();
                $request->session()->put('isLogin', true);
                $request->session()->put('username', $loggedInUser->email);
                $request->session()->put('id_api', $loggedInUser->id); // Pakai ID lokal
                $request->session()->put('api_role', $level_name); // Role manual
                $request->session()->put('status', '1');
                $request->session()->put('level', $lvl);
                $request->session()->put('level_name', $level_name);
                $request->session()->put('role_prodi', []); // Kosongkan saja untuk dev

                // 4. Redirect ke dashboard yang sesuai
                return $this->redirectUserBasedOnLevel($lvl);
            }

            // 5. Jika password 'password' tapi email tdk ada di DB lokal
            return back()->with('msg', 'Akun dev tidak ditemukan. Jalankan seeder.');
        }
        // ========================================================
        // ================= AKHIR DARI DEV LOGIN =================
        // ========================================================


        // Jika BUKAN login developer, baru jalankan pengecekan API UBP
        $url = 'https://api-gateway.ubpkarawang.ac.id/auth/login';

        try {
            $response = Http::asForm()->post($url, [
                'email' => $email,
                'password' => $password,
            ]);
            $data_json = $response->object();
        } catch (\Exception $e) {
            return back()->with('msg', 'Tidak dapat terhubung ke server otentikasi. Coba lagi nanti.');
        }

        // Logika API (Tidak berubah)
        if (isset($data_json->status_code) && $data_json->status_code == '000') {
            
            $data_data = $data_json->data;
            
            // --- SINKRONISASI USER DARI API KE DATABASE LOKAL ---
            $user = User::updateOrCreate(
                ['email' => $data_data->email], // Kunci pencarian
                [
                    'name' => $data_data->nama ?? $data_data->email, // Ambil nama dari API
                    'password' => Hash::make($password), // Simpan password
                ]
            );

            // Login-kan user yang baru disinkronkan
            Auth::login($user);
            
            $request->session()->regenerate();
            $request->session()->put('isLogin', true);
            $request->session()->put('username', $data_data->email);
            $request->session()->put('id_api', $data_data->id_api); // Ambil id dari API
            $request->session()->put('api_role', $data_data->role); 
            $request->session()->put('status', '1');

            // Logika $lvl Anda
            $lvl = 0;
            $level_name = 'guest';
            $role_prodi = [];

            if (Str::contains($email, '@mhs.ubpkarawang.ac.id')) {
                if ($data_data->role == 'mahasiswa') {
                    $lvl = 2;
                    $level_name = 'mahasiswa';
                }
            } else if (Str::contains($email, '@ubpkarawang.ac.id') || Str::contains($email, '@ptk.ubpkarawang.ac.id')) {
                if ($data_data->role == "pegawai" || $data_data->role == "tendik") {
                    $lvl = 3;
                    $level_name = 'dosen';
                    if (Str::contains($data_data->user_access, 'akademik')) {
                        $lvl = 6; 
                        $level_name = 'fakultas';
                        array_push($role_prodi, $data_data->prodi);
                    } elseif (Str::contains($data_data->user_access, 'korprodi')) {
                        $lvl = 4;
                        $level_name = 'koordinator';
                        array_push($role_prodi, $data_data->prodi);
                    } elseif (Str::contains($data_data->user_access, 'tu:')) {
                        $lvl = 5; 
                        $level_name = 'tata_usaha';
                    }
                }
            }
            
            // Simpan role ke database lokal
            $user->role = $level_name;
            $user->save();
            
            $request->session()->put('level', $lvl);
            $request->session()->put('level_name', $level_name);
            $request->session()->put('role_prodi', $role_prodi);

            // Logika redirect Anda
            return $this->redirectUserBasedOnLevel($lvl);

        } else {
            $message = $data_json->message ?? 'Email atau password salah!';
            return back()->with('msg', $message);
        }
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        $request->session()->forget([
            'isLogin', 'username', 'id_api', 'api_role',
            'status', 'level', 'level_name', 'role_prodi'
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Helper function untuk mengarahkan user berdasarkan level.
     * Ini adalah versi yang sudah DIPERBAIKI.
     */
    private function redirectUserBasedOnLevel($lvl)
    {
        switch ($lvl) {
            case 2: return redirect()->route('mahasiswa.dashboard');
            case 3: return redirect()->route('dosen.dashboard');
            case 4: return redirect()->route('koordinator.dashboard'); // <-- INI PERBAIKANNYA
            case 5: return redirect()->route('tata_usaha.dashboard');
            case 6: return redirect()->route('fakultas.dashboard');
            default:
                Auth::logout();
                return redirect('/login')->with('msg', 'Role tidak dikenal.');
        }
    }
}