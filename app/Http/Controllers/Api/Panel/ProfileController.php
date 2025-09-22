<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return api_response([
            'name' => $user->name,
            'email' => $user->email,

        ]);

    }
    public function profile(Request $request)
    {
        $request->validate([
            'name' => 'nullable',
            'email' => 'nullable',
            'profile'=> 'nullable'
        ]);
        $imagePath = $request->file('profile')->store('profiles', 'public');

        User::update([
            'name' => $request->name,
            'email' => $request->email,
            'profile' => $imagePath,
        ]);
        return api_response([],'با موفقیت اپدیت شد');
    }
    public function changePassword(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'old_password' => 'required',
            'password' => 'required',
        ]);
        if ($user->password === bcrypt($request->old_password)) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
            return api_response([],'رمز تغییر کرد');
        }

        return api_response([],'رمز قدیمی اشتباه است');
    }
}
