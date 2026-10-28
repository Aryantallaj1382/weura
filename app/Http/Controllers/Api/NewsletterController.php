<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email|max:255',
            ],
            [
                'email.required' => 'ایمیل الزامی است.',
                'email.email' => 'فرمت ایمیل وارد شده معتبر نیست.',
                'email.max' => 'ایمیل نباید بیشتر از ۲۵۵ کاراکتر باشد.',
            ]
        );

        $email = $request->email;

        if (Newsletter::where('email', $email)->exists()) {
            return api_response([], 'این ایمیل قبلا در خبرنامه ثبت شده است با تشکر از شما.' , 422);
        }

        Newsletter::create(['email' => $email]);

        return api_response([], 'ایمیل شما با موفقیت در خبرنامه ثبت شد.');
    }
}
