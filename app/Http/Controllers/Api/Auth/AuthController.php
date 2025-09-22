<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\TempUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use IPPanel\Client;
use Throwable;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {

        $data = $request->validate([
            'mobile' => 'required|regex:/^[0-9]{10,15}$/',

        ],[], [
            'mobile' => 'موبایل ',
        ]);


        $user = User::where('mobile', $data['mobile'])->first();

        if (blank($user)) {
            $user = TempUser::firstOrCreate([
              'mobile' => $data['mobile'],
            ], [
                'sms_sent_tries' => 0,
                'sms_sent_code' => null,
            ]);
        }

        $seconds = optional($user->sms_sent_date)->diffInSeconds(now());
        $remaining = round(120 - $seconds);
//        if ($seconds && $seconds < 120) {
//            $remaining = round(120 - $seconds); // گرد کردن به عدد صحیح
//            return api_response([
//                'remain' => $remaining],
//                "لطفاً پس از گذشت " . $remaining . " ثانیه دوباره تلاش کنید."
//                , 429);
//        }

        $code = random_int(100000, 999999);

        $user->update([
            'sms_sent_tries' => 0,
            'sms_sent_date' => now(),
            'sms_sent_code' => $code,
        ]);

        $responseMessages = [
            'remain' => $remaining,

        ];

        try {
            if (!empty($data['mobile'])) {
                (new Client(config('app.sms_panel_apikey')))
                    ->sendPattern(
                        'i5ptms6ccqcs3e5',
                        '3000505',
                        $data['mobile'],
                        ['code' => $code]
                    );
                $responseMessages['message'] = 'کد تایید به شماره موبایل ارسال شد.';

            }


        } catch (Throwable $e) {

            return response()->json([
                'message' => $e->getMessage(),   // متن خطا
                'file'    => $e->getFile(),      // فایل خطا
                'line'    => $e->getLine(),      // خطی که خطا اتفاق افتاده
                'trace' => $e->getTraceAsString() // اگر بخوای کل استک ترِیس بیاد
            ], 500);
        }
        return api_response($responseMessages, 'کد تایید برای شما ارسال شد');
    }

    public function checkUserExists(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string',
        ]);

        $user = User::where('mobile', $request->input('mobile'))
            ->first();
        if ($user) {
            return api_response([
                'exists' => true,
                'message' => 'کاربر با این  شماره موبایل قبلاً ثبت‌نام کرده است.'
            ]);
        } else {
            return api_response([
                'exists' => false,
                'message' => 'کاربری با این  شماره موبایل وجود ندارد.'
            ]);
        }
    }
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return api_response([],'با موفقیت از حساب خود خارج شدید ');
    }
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'mobile'   => 'required|numeric|unique:users,mobile',
            'password' => 'required|string|min:6',
            'sms_code' => 'required|string',
        ]);

        // پیدا کردن temp_user مربوط به این موبایل
        $tempUser = TempUser::
            where('mobile', $request->mobile)
            ->first();

        if (!$tempUser) {
            return response()->json(['message' => 'کد تأیید پیدا نشد'], 422);
        }

        // بررسی تطابق کد
        if ($tempUser->sms_sent_code !== $request->sms_code) {
            return response()->json(['message' => 'کد وارد شده اشتباه است'], 422);
        }

        // بررسی زمان (حداکثر 2 دقیقه)
        $sentAt = \Carbon\Carbon::parse($tempUser->sms_sent_date);
        if ($sentAt->diffInMinutes(now()) > 2) {
            return response()->json(['message' => 'کد منقضی شده است'], 422);
        }

        // ایجاد کاربر
        $user = User::create([
            'name'     => $request->name,
            'mobile'   => $request->mobile,
            'password' => \Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return api_response([
            'token'   => $token,
        ] , 'ثبت نام با موفقیت انجام شد');
    }


    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required|numeric',
            'password' => 'required|string',
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'mobile' => ['شماره موبایل یا رمز عبور اشتباه است.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return api_response([
            'token' => $token,
            'role' => $user->role,
        ],'ورود موفقیت‌آمیز');
    }

    public function resetPasswordWithOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string',
            'otp' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $mobile = $request->input('mobile');
        $otp = $request->input('otp');
        $user = User::where('mobile', $mobile)->first();

        if (! $user) {
            return response()->json(['message' => 'کاربر با این شماره یافت نشد.'], 404);
        }
        if (! isset($user->sms_sent_code)) {
            return response()->json(['message' => 'سیستم پشتیبانی OTP در جدول users فعال نیست.'], 500);
        }
        $sentAt = \Carbon\Carbon::parse($user->sms_sent_date);
        if ($sentAt->diffInMinutes(now()) > 2) {
            return response()->json(['message' => 'کد منقضی شده است'], 422);
        }

        if (! hash_equals((string) $user->sms_sent_code, (string) $otp)) {
            return response()->json(['message' => 'کد تایید نادرست است.'], 400);
        }
        $user->password = Hash::make($request->input('password'));


        $user->save();

        return api_response([],'رمز با موفقیت تغییر یافت');
    }
}
