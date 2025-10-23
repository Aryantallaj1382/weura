<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthorRequest;
use Illuminate\Http\Request;
class AuthorRequestController extends Controller
{
    public function index()
    {
        $requests = AuthorRequest::latest()->paginate(10);
        return view('admin.author_requests.index', compact('requests'));
    }

    public function show($id)
    {
        $request = AuthorRequest::findOrFail($id);
        return response()->json($request); // برای مدال Ajax
    }

}
