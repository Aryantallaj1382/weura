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

        $code = random_int(10000, 99999);


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
                        '5kybw03wwfjfn3v',
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
        return api_response([
            'remain'=>120,
//            'code' => $code
        ], 'کد تایید برای شما ارسال شد');
    }

    public function checkUserExists(Request $request)
    {
        $request->validate([
            'mobile' => 'required|string',
        ]);
        $user = User::where('mobile', $request->input('mobile'))->first();
        if ($user) {
            return api_response([
                'is_exist' => true,
                'message' => 'کاربر با این  شماره موبایل قبلاً ثبت‌نام کرده است.'
            ]);
        } else {
            return api_response([
                'is_exist' => false,
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
            'user_name' => 'required',
            'password' => 'required',
        ]);


        $user = auth()->user();
        if (\App\Models\User::where('user_name', $request->user_name)
            ->where('id', '!=', $user->id)
            ->exists()) {

            return api_response([], 'نام کاربری قبلاً انتخاب شده است.', 422);
        }
        $user = $user->update([
            'user_name' => $request->user_name,
            'password' => bcrypt($request->password),
        ]);
        return api_response([],'ثبت نام با موفقیت انجام شد', 200);
    }
    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'password' => 'nullable',
            'otp' => 'nullable',
        ]);
        if (!empty($request->otp) && !empty($request->password)) {
            return api_response([], 'لطفا فقط یکی از رمز یا کد پیامکی را وارد کنید', 422);
        }

        $user = User::where('mobile',$request->mobile)->first();
        if(!$user){
            $tempUser = TempUser::
            where( 'mobile', $request->mobile)
                ->first();

            if (!$tempUser) {
                return response()->json(['message' => 'کد تأیید پیدا نشد'], 422);
            }

            if ($tempUser->sms_sent_code !== $request->otp) {
                return response()->json(['message' => 'کد وارد شده اشتباه است'], 422);
            }

            $sentAt = \Carbon\Carbon::parse($tempUser->sms_sent_date);
            if ($sentAt->diffInMinutes(now()) > 2) {
                return response()->json(['message' => 'کد منقضی شده است'], 422);
            }
            $user = User::create([
                'mobile'   => $request->mobile,
                'password' => \Hash::make($request->password),
            ]);
            $token = $user->createToken('auth_token')->plainTextToken;

            return api_response([
                'token'   => $token,
                'mobile' => $user->mobile,

            ] , 'ثبت نام با موفقیت انجام شد');
        }
        if (!empty($request->password)) {
            if (!Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'mobile' => ['شماره موبایل یا رمز عبور اشتباه است.'],
                ]);
            }
        }
        if (!empty($request->otp)) {
            if ($user->sms_sent_code !== $request->otp) {
                throw ValidationException::withMessages([
                    'otp' => ['کد وارد شده نامعتبر است.'],
                ]);
            }

            $user->update([
                'sms_sent_code' => null,
            ]);
        }
        if (empty($request->otp) && empty($request->password)) {
            return api_response([], 'لطفا رمز عبور یا کد پیامکی را وارد کنید', 422);
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return api_response([
            'token' => $token,
            'mobile' => $user->mobile,
        ], 'ورود موفقیت‌آمیز');
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
