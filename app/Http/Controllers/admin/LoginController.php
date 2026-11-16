<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class LoginController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function show()
    {
        if (Auth::check()) {
            return redirect()->route('crm.dashboard.index');
        }

        return view('crm.login.login');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Username wajib diisi',
            'password.required' => 'Password wajib diisi',
        ]);

        try {
            $this->authService->login($data['username'], $data['password'], $request->ip());
        } catch (HttpExceptionInterface $e) {
            // 401 (kredensial salah) atau 429 (terkunci): tampil di @error('login_failed') pada login.blade.php
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['login_failed' => $e->getMessage()]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('crm.dashboard.index'));
    }

    public function destroy(Request $request)
    {
        $this->authService->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
