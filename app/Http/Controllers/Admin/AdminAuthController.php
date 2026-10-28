<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{


    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->where('role','admin')->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'ایمیل یا رمز عبور اشتباه است',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

}
