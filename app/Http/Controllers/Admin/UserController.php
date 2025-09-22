<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        $users->appends($request->only('search'));

        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with(['wallet.transactions'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }
    public function updateStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:successful,pending,failed',
        ]);

        if ($transaction->status === 'pending' && $request->status === 'successful') {
            $wallet = $transaction->wallet;
            if ($wallet) {
                $wallet->balance -= $transaction->amount;
                $wallet->save();
            }
        }

        // تغییر وضعیت تراکنش
        $transaction->status = $request->status;
        $transaction->save();

        return back()->with('success', 'وضعیت تراکنش با موفقیت تغییر کرد.');
    }


}
