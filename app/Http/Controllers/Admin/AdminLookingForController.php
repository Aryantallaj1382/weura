<?php

namespace App\Http\Controllers\Admin;

use App\Models\Banner;
use App\Models\Category;
use App\Models\LookingFor;
use App\Models\Manhwa;
use Illuminate\Http\Request;

class AdminLookingForController
{
    public function index()
    {
        $looking = LookingFor::get();
        return view('admin.looking.index', compact('looking'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'logo' => 'required',
        ]);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $fileName = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('uploads/LookingFor'), $fileName);

            $data['logo'] = 'uploads/LookingFor/' . $fileName;
        }


        LookingFor::create($data);

        return redirect()
            ->route('admin.admin.looking.index')
            ->with('success', ' با موفقیت ایجاد شد.');
    }

    public function destroy(LookingFor $looking)
    {
        $looking->delete();

        return redirect()
            ->route('admin.admin.looking.index')
            ->with('success','با موفقیت حذف شد.');
    }
}
