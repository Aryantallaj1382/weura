<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'required|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        if ($request->hasFile('icon')) {
            $file = $request->file('icon');
            $fileName = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('uploads/categories'), $fileName);

            $data['icon'] = 'uploads/categories/' . $fileName;
        }

        Category::create($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'دسته‌بندی با موفقیت ایجاد شد.');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|image|mimes:jpg,jpeg,png,webp,svg|max:2048',
        ]);

        if ($request->hasFile('icon')) {

            if ($category->icon && File::exists(public_path($category->icon))) {
                File::delete(public_path($category->icon));
            }

            $file = $request->file('icon');
            $fileName = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('uploads/categories'), $fileName);

            $data['icon'] = 'uploads/categories/' . $fileName;
        }

        $category->update($data);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'دسته‌بندی با موفقیت ویرایش شد.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'دسته‌بندی با موفقیت حذف شد.');
    }
}
