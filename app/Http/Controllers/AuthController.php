<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Mahasiswa;

class AuthController extends Controller
{
    /**
     * Tampilkan halaman login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses login menggunakan API Gateway dan database lokal
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $email = $request->input('email');
        $password = $request->input('password');

        // Cari user di database lokal
        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            // Login user lokal
            Auth::login($user);
            $request->session()->regenerate();

            return $this->redirectByRole($user->role);
        }

        // Jika user tidak ada atau password salah, cek API
        try {
            $response = Http::asForm()->post('https://api-gateway.ubpkarawang.ac.id/auth/login', [
                'email'    => $email,
                'password' => $password,
            ]);

            $data_json = $response->object();
        } catch (\Exception $e) {
            return back()->with('msg', 'Tidak dapat terhubung ke server otentikasi. Coba lagi nanti.');
        }

        if (!isset($data_json->status_code) || $data_json->status_code !== '000') {
            $message = $data_json->message ?? 'Email atau password salah!';
            return back()->with('msg', $message);
        }

        $data = $data_json->data;

        // Sinkronisasi user ke database lokal
        $user = User::updateOrCreate(
            ['email' => $data->email],
            [
                'name'     => $data->nama ?? $data->email,
                'password' => Hash::make($password),
                'role'     => $this->determineRole($data),
            ]
        );

        // Login user
        Auth::login($user);
        $request->session()->regenerate();

        return $this->redirectByRole($user->role);
    }

    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ====================== HELPERS ===========================

    /**
     * Tentukan role user berdasarkan data API
     */
    private function determineRole($data)
    {
        $email = $data->email;

        if (Str::contains($email, '@mhs.ubpkarawang.ac.id') && $data->role === 'mahasiswa') {
            return 'mahasiswa';
        }

        if (
            Str::contains($email, ['@ubpkarawang.ac.id', '@ptk.ubpkarawang.ac.id']) &&
            in_array($data->role, ['pegawai', 'tendik'])
        ) {
            if (Str::contains($data->user_access, 'korprodi')) return 'koordinator';
            if (Str::contains($data->user_access, 'tu:')) return 'tata_usaha';
            if (Str::contains($data->user_access, 'akademik')) return 'fakultas';
            return 'dosen';
        }

        return 'guest';
    }

    /**
     * Redirect user berdasarkan role
     */
    private function redirectByRole($role)
    {
        return match ($role) {
            'mahasiswa'   => redirect()->route('mahasiswa.dashboard'),
            'dosen'       => redirect()->route('dosen.dashboard'),
            'koordinator' => redirect()->route('koordinator.dashboard'),
            'tata_usaha'  => redirect()->route('tata_usaha.dashboard'),
            'fakultas'    => redirect()->route('fakultas.dashboard'),
            'admin'       => redirect()->route('admin.dashboard'),
            default       => redirect()->route('login')->with('msg', 'Role tidak dikenal.')
        };
    }
}
