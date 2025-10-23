<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = $request->email;

        if (Newsletter::where('email', $email)->exists()) {
            return api_response([], 'این ایمیل قبلا در خبرنامه ثبت شده است.' , 422);
        }

        Newsletter::create(['email' => $email]);

        return api_response([], 'با موفقیت در خبرنامه ثبت شد.');
    }
}
