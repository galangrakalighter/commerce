<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);

            $remember = $request->has('remember');

            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();

                return response()->json([
                    'success' => true,
                    'message' => 'Selamat datang kembali, ' . Auth::user()->name . '!',
                    'user' => Auth::user()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Email atau password yang Anda masukkan salah.'
            ], 422);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first()
            ], 422);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $isAdmin = User::count() === 0;

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'is_admin' => $isAdmin,
            ]);

            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Akun berhasil dibuat! Selamat datang, ' . $user->name,
                'user' => $user
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => collect($e->errors())->flatten()->first()
            ], 422);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Bersihkan dan invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->back()->with('success', 'Anda telah berhasil keluar.');
    }
}
