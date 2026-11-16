<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function login(LoginRequest $request)
    {
        $user = $this->authService->login($request->username, $request->password, $request->ip());

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data'    => ['username' => $user->username, 'name' => $user->name],
        ]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['success' => true, 'message' => 'Logout berhasil']);
    }
}