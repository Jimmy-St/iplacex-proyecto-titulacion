<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $user_input = $request->input('user');
        $pass_input = $request->input('password');

        if ($user_input === 'admin' && $pass_input === '12345678') {

            $user = User::firstOrCreate(
                ['email' => 'admin@pfau.cl'],
                ['name' => 'Admin', 'password' => bcrypt('123456')]
            );

            Auth::login($user);
            $request->session()->regenerate();

            return redirect()->intended('/tickets');
        }

        return back()->with('error', 'Credenciales incorrectas');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
