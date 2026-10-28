<?php

namespace App\Helpers;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class zarinpal
{


    private int $amount;
    private string $api_key;

    public function __construct(?string $api_key = null)
    {
        if (filled($api_key))
            $this->api_key = $api_key;
        else
            $this->api_key = config('zarinpal.merchant_id');
    }
    public function pay($user_id,int $amount ,string $callbackUrl_input)
    {

        $this->set_amount($amount);

        $response = zarinpal()
            ->merchantId(config('zarinpal.merchant_id'))
            ->amount($amount)
            ->request()
            ->description($description ?? 'پرداخت')
            ->callbackUrl($callbackUrl_input)
            ->send();

        if (!$response->success()) {
        throw new \Exception($response->error()->message());
    }
     payment::create([
            'user_id' => $user_id,
            'gateway' => 'zarinpal',
            'amount' => $amount,
            'authority' => $response->authority(),
            'status' => 'pending',
        ]);

        return [
            'url' => 'https://payment.zarinpal.com/pg/StartPay/' . $response->authority(),
            'authority' => $response->authority()

        ];
    }

    public function verify()
    {
        $authority = request('Authority');
        $status = request('Status');

        $payment = Payment::where('authority', $authority)->firstOrFail();

        $response = zarinpal()
            ->merchantId(config('services.zarinpal.merchant_id'))
            ->amount($payment->amount)
            ->verification()
            ->authority($authority)
            ->send();

        if (!$response->success()) {
            $payment->update(['status' => 'failed']);
            throw new \Exception($response->error()->message());
        }

        $payment->update([
            'status' => 'paid',
            'ref_id' => $response->referenceId(),
            'meta' => [
                'card_pan' => $response->cardPan(),
                'card_hash' => $response->cardHash(),
            ]
        ]);

        return $payment;
    }

    public function set_amount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }
    private function generateUniqueCode($userId)
    {
        $today = Carbon::today()->format('Ymd');

        $unique = md5($userId . $today . microtime());

        $part1 = $userId;
        $part2 = substr(preg_replace('/[^0-9]/', '', hexdec(substr($unique, 0, 6))), 0, 2);
        $part3 = substr(preg_replace('/[^0-9]/', '', hexdec(substr($unique, 6, 6))), 0, 2);
        $part4 = substr(preg_replace('/[^0-9]/', '', hexdec(substr($unique, 12, 6))), 0, 2);

        return "{$part1}-{$part2}-{$part3}-{$part4}";
    }
}
