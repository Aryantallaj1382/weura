<?php

namespace App\Http\Controllers\Api\Auther;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        $transactions = $wallet->transactions->map(function ($transaction) use ($user) {
            return [
                'id' => $transaction->id,
                'operation_type	' => $transaction->operation_type,
                'amount' => $transaction->amount,
                'status' => $transaction->status,
                'created_at' => $transaction->created_at,
            ];
        });
        return api_response([
            'balance' => $wallet->balance,
            'transactions' => $transactions,
        ]);
    }
}
