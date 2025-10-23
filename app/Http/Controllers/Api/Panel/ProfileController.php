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
            'name'                 => $user->name,
            'email'                => $user->email,
            'profile'              => $user->profile,
            'notify_new_chapter'    => $user->notify_new_chapter,
            'notify_user_referral'  => $user->notify_user_referral,
            'notify_promotions'     => $user->notify_promotions,
            'notify_donation'       => $user->notify_donation,
        ]);
    }

    public function profile(Request $request)
    {
        $request->validate([
            'name'                  => 'nullable|string|max:255',
            'email'                 => 'nullable|email|max:255',
            'profile'               => 'nullable|image|max:2048', // عکس پروفایل
            'notify_new_chapter'    => 'nullable|boolean',
            'notify_user_referral'  => 'nullable|boolean',
            'notify_promotions'     => 'nullable|boolean',
            'notify_donation'       => 'nullable|boolean',
        ]);

        $user = auth()->user();

        if ($request->hasFile('profile')) {
            $imagePath = $request->file('profile')->store('profiles', 'public');
            $user->profile = $imagePath;
        }
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('notify_new_chapter')) $user->notify_new_chapter = $request->notify_new_chapter;
        if ($request->has('notify_user_referral')) $user->notify_user_referral = $request->notify_user_referral;
        if ($request->has('notify_promotions')) $user->notify_promotions = $request->notify_promotions;
        if ($request->has('notify_donation')) $user->notify_donation = $request->notify_donation;

        $user->save();

        return api_response([], 'با موفقیت آپدیت شد');
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
