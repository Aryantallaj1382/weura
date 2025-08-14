<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public function index()
    {
        $categories = BlogCategory::latest()->paginate(10);
        return view('admin.BlogCategories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.BlogCategories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        BlogCategory::create($data);

        return redirect()->route('admin.blog_categories.index')->with('success', 'دسته‌بندی با موفقیت ایجاد شد.');
    }

    public function edit(BlogCategory $BlogCategory)
    {
        return view('admin.BlogCategories.edit', compact('BlogCategory'));
    }

    public function update(Request $request, BlogCategory $BlogCategory)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $BlogCategory->update($data);

        return redirect()->route('admin.blog_categories.index')->with('success', 'دسته‌بندی با موفقیت ویرایش شد.');
    }

    public function destroy(BlogCategory $BlogCategory)
    {
        $BlogCategory->delete();
        return redirect()->route('admin.blog_categories.index')->with('success', 'دسته‌بندی با موفقیت حذف شد.');
    }
}
