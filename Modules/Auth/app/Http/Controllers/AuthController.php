<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    public function index()
    {
        $title = 'Login';

        return view('auth::index', [
            'title' => $title,
        ]);
    }

    public function login(LoginRequest $request)
    {
        $fieldType = filter_var($request->identity, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $fieldType => $request->identity,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard.index');
        }

        return back()->withErrors([
            'identity' => 'Your provided credentials do not match our records.',
        ])->onlyInput('identity');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('auth.index');
    }
}
