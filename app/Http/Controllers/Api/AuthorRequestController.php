<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuthorRequest;
use App\Models\TempUser;
use App\Models\User;
use Illuminate\Http\Request;
class AuthorRequestController extends Controller
{
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'digital_painting_skill' => 'required',
            'writing_skill' => 'required',
            'sample_file' => 'nullable',
            'software' => 'nullable|string|max:255',
            'need_support' => 'nullable',
            'otp' => 'required',
            'social_media' => 'nullable',
        ]);

        $phone = User::where('mobile', $validated['phone'])->first();
        $r = AuthorRequest::where('phone', $validated['phone'])->first();
        if ($phone || $r)
        {
            return api_response([],'این شماره قبلا ثبت نام کرده',401);
        }
        $tempUser = TempUser::where( 'mobile', $validated['phone'])->first();

        if (!$tempUser) {

            return response()->json(['message' => 'این شماره قبلا ثبت نام کرده'], 422);
        }

        if ($tempUser->sms_sent_code !==  $validated['otp']) {
            return response()->json(['message' => 'کد وارد شده اشتباه است'], 422);
        }

        $sentAt = \Carbon\Carbon::parse($tempUser->sms_sent_date);
        if ($sentAt->diffInMinutes(now()) > 5) {
            return response()->json(['message' => 'کد منقضی شده است'], 422);
        }
        if ($request->hasFile('sample_file')) {
            $file = $request->file('sample_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('author_samples');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);

            $validated['sample_file'] = 'author_samples/' . $filename;
        }

        $authorRequest = AuthorRequest::create([
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'digital_painting_skill' => $request->digital_painting_skill,
                'writing_skill' => $request->writing_skill,
                'sample_file' => $filename ?? null,
                'software' => $request->software,
                'need_support' => $request->need_support,
                'social_media' => $request->social_media,
            ]
        );
        $user = User::create([
            'mobile'   => $request->phone,
            'name'   => $request->name,
            'user_name'   => $request->user_name,
            'password' => \Hash::make($request->password),
            'role'     => 'author',

        ]);
        $token = $user->createToken('auth_token')->plainTextToken;

        return api_response([
            'token' => $token,
        ], 'ثبت نام شما با موفقیت انجام شد میتوانید وارد پنل شوید');
    }
}
