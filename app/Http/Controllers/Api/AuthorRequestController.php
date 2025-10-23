<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AuthorRequest;
use Illuminate\Http\Request;
class AuthorRequestController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'digital_painting_skill' => 'required',
            'writing_skill' => 'required',
            'sample_file' => 'nullable',
            'software' => 'nullable|string|max:255',
            'need_support' => 'boolean',
        ]);

        if ($request->hasFile('sample_file')) {
            $validated['sample_file'] = $request->file('sample_file')->store('author_samples', 'public');
        }

        $authorRequest = AuthorRequest::create($validated);

        return response()->json([
            'message' => 'درخواست شما با موفقیت ثبت شد.',
            'data' => $authorRequest,
        ], 201);
    }
}
