<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ComingSoonManhua;
use Illuminate\Support\Facades\Storage;

class ComingSoonManhuaController extends Controller
{
    // نمایش لیست
    public function index()
    {
        $manhuas = ComingSoonManhua::latest()->paginate(10);
        return view('admin.CommingSoon.index', compact('manhuas'));
    }

    // ذخیره رکورد جدید
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'image' => 'required|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName(); // نام یکتا
            $file->move(public_path('images/coming_soon'), $filename); // ذخیره مستقیم در public/images/coming_soon
            $data['image'] = 'images/coming_soon/' . $filename; // مسیر برای دیتابیس
        }

        ComingSoonManhua::create($data);

        return redirect()->route('admin.coming_soon_manhuas.index')
            ->with('success', 'مانهوی جدید با موفقیت اضافه شد.');
    }


    // نمایش فرم ویرایش (ما با مدال انجام می‌دهیم، نیاز به view جدا نیست)
    public function edit($id)
    {
        $manhua = ComingSoonManhua::findOrFail($id);
        return response()->json($manhua);
    }

    // بروزرسانی رکورد
    public function update(Request $request, $id)
    {
        $manhua = ComingSoonManhua::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // حذف تصویر قبلی
            if ($manhua->image) {
                Storage::disk('public')->delete($manhua->image);
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . $file->getClientOriginalName(); // نام یکتا
                $file->move(public_path('images/coming_soon'), $filename); // ذخیره مستقیم در public/images/coming_soon
                $data['image'] = 'images/coming_soon/' . $filename; // مسیر برای دیتابیس
            }
        }

        $manhua->update($data);

        return redirect()->route('admin.coming_soon_manhuas.index')->with('success', 'اطلاعات مانهوی با موفقیت بروزرسانی شد.');
    }

    // حذف رکورد
    public function destroy($id)
    {
        $manhua = ComingSoonManhua::findOrFail($id);

        // حذف تصویر
        if ($manhua->image) {
            Storage::disk('public')->delete($manhua->image);
        }

        $manhua->delete();

        return redirect()->route('admin.coming_soon_manhuas.index')->with('success', 'مانهوی با موفقیت حذف شد.');
    }
}
