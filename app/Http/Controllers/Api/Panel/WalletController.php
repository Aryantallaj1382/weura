<?php

namespace App\Http\Controllers\Api\Panel;

use App\Helpers\zarinpal;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $wallet = Wallet::where('user_id', $user->id)->first();
        if (!$wallet) {
            $wallet = Wallet::create([
                'user_id' => $user->id,
                'balance' => 0
            ]);
        }

        $tranches = Transaction::where('wallet_id', $wallet->id)->paginate(10);
        $tranches->getCollection()->transform(function ($item, $key) use ($tranches) {
            return [
                'id' => $tranches->firstItem() + $key,
                'amount' => (int) $item->amount,
                'operation_type' => $item->operation_type,
                'status' => $item->status,
                'date' => Jalalian::fromDateTime($item->created_at)->format('M d'),
                'time' => Jalalian::fromDateTime($item->created_at)->format('H:i'),
            ];
        });
        return api_response($tranches, '', 200, ['balance' => (int)$wallet->balance]);

    }

    public function withdrawal(Request $request)
    {
        $user = auth()->user();
        $request->validate(
            [
                'amount' => 'required',
                'cart_number' => 'required',
            ],
            [
                'amount.required' => 'مبلغ الزامی است.',
                'cart_number.required' => 'شماره کارت الزامی است.',
            ]
        );
        $wallet = Wallet::where('user_id', $user->id)->first();
        if ($wallet->balance < $request->amount) {
            return api_response([],'مبلغ بیشتر از موجودی شما میباشد',422);
        }
        $tranches = Transaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'operation_type' => 'withdrawal',
            'status' => 'pending',
            'cart_number' => $request->cart_number,

        ]);


        return api_response([], 'در خواست شما با موفقیت ثبت شد');


    }
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required',
        ]);
        $user = auth()->user();

        $paymentService = new zarinpal();
        $callback ='https://api.weura.ir/zarin_callback';
        return $paymentService->pay($user->id,$request->amount , $callback);
    }

    public function zarin_callback(Request $request)
    {
        if (!$request->filled('Authority') || !$request->filled('Status'))
            return api_response([], 'خطا در پاسخ درگاه پرداخت', 403);

        $payment = Payment::where('authority', '=', $request->Authority)->first();


        if (blank($payment))
            return api_response([], 'اطلاعات پرداخت حذف شده است', 403);


        $price =  $payment->amount;
        $authority = $payment->authority;
        $response = zarinpal()
            ->merchantId(config('zarinpal.merchant_id'))
            ->amount($price)
            ->verification()
            ->authority($authority)
            ->send();
        $redirect_url = 'https://weura.ir/' ;
        $user = User::find($payment->user_id);
        if (!$response->success()) {
            $transaction = Transaction::create([
                'wallet_id' => $user->wallet->id,
                'operation_type' => 'deposit',
                'status' => 'failed',
                'amount' => $price,

            ]);
            $payment->update([
                'status' => 'failed',
                'transaction_id' => $transaction->id,
            ]);
            return redirect($redirect_url);
        }
        $ref =  $response->referenceId();
        $transaction = Transaction::create([
            'wallet_id' => $user->wallet->id,
            'operation_type' => 'deposit',
            'status' => 'successful',
            'amount' => $price,

        ]);
        $payment->update([
           'status' => 'paid',
            'transaction_id' => $transaction->id,
            ]);
        $wallet = Wallet::where('user_id', $user->id)->first();
        $wallet->balance = $wallet->balance + $price;


        return redirect($redirect_url);
    }

}
