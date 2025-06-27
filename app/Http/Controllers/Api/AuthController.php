<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Auth;
use Carbon\Carbon;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|string'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role
        ]);

        return response()->json(['message' => 'Registrasi berhasil', 'user' => $user], 201);
    }

    // LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah'], 401);
        }

        $token = Str::random(60);
        $expiredAt = Carbon::now()->addDays(7);

        Auth::updateOrCreate(
            ['user_id' => $user->id],
            ['token' => $token, 'token_expired_at' => $expiredAt]
        );

        return response()->json([
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => $user
        ]);
    }

    // LOGOUT
    public function logout(Request $request)
    {
        $user = $request->get('auth_user');

        if ($user) {
            Auth::where('user_id', $user->id)->delete();
            return response()->json(['message' => 'Logout berhasil']);
        }

        return response()->json(['message' => 'User tidak ditemukan'], 404);
    }
}
