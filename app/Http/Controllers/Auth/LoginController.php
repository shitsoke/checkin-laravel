<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)
                    ->where('is_banned', 0)
                    ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found or banned.'])->withInput();
        }

        if (is_null($user->email_verified_at)) {
            return back()->withErrors(['email' => 'Email not verified.'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Wrong password.'])->withInput();
        }

        Auth::login($user, $request->remember);

        /* if ($user->role && $user->role->name === 'admin') { 
            return redirect()->route('admin');
        } */

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
