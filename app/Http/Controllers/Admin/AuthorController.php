<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Manhwa;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role' , 'writer');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')->orWhere('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);

        $users->appends($request->only('search'));

        return view('admin.authors.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with(['wallet.transactions'])->findOrFail($id);
        $manhuas = Manhwa::where('author_id', $id)->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.authors.show', compact(['user' , 'manhuas']));
    }


}
