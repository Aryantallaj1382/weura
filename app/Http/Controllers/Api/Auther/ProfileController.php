<?php

namespace App\Http\Controllers\Api\Auther;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
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
            'user_name' => $request->user_name,
            'email' => $request->email,
            'profile' => $imagePath,
        ]);
        return api_response([],'با موفقیت اپدیت شد');
    }
    public function changePassword(Request $request)
    {
        $user = auth()->user();
        $request->validate(
            [
                'old_password' => 'required',
                'password' => 'required',
            ],
            [
                'old_password.required' => 'رمز عبور فعلی الزامی است.',

                'password.required' => 'رمز عبور جدید الزامی است.',
            ]
        );
        if ($user->password === bcrypt($request->old_password)) {
            $user->update([
                'password' => bcrypt($request->password),
            ]);
            return api_response([],'رمز تغییر کرد');
        }

        return api_response([],'رمز قدیمی اشتباه است');
    }
}
