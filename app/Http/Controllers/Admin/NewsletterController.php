<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;

class NewsletterController extends Controller
{
    public function index()
    {
        $emails = Newsletter::latest()->paginate(15);
        return view('admin.newsletters.index', compact('emails'));
    }

    // حذف ایمیل
    public function destroy($id)
    {
        $email = Newsletter::findOrFail($id);
        $email->delete();

        return redirect()->route('admin.newsletters.index')
            ->with('success', 'ایمیل با موفقیت حذف شد.');
    }
}
