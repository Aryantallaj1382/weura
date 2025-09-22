<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'mobile' => 'required',
            'text' => 'required',
            'type' => 'required',
        ]);
        ContactUs::create($request->all() );
        return api_response([],'پیام شما ثبت شد با تشکر');
    }
    public function blogs()
    {
        $blogs = Blog::latest()->take(6)->get()->map(function ($blog) {
            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'image' => $blog->image,
                'category' => $blog->category->name,
                'author' => $blog->author,
                'created_at' => $blog->created_at,
            ];
        });
        api_response($blogs);

    }
}
