<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // گرفتن همه کاربران مرتب بر اساس جدیدترین‌ها
        $users = User::orderBy('created_at', 'desc')->paginate(10);

        // نمایش در ویو
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

        $transaction->status = $request->status;
        $transaction->save();

        return back()->with('success', 'وضعیت تراکنش با موفقیت تغییر کرد.');
    }

}
