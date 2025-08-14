<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Manhwa;
use Illuminate\Http\Request;

class ManhwaController extends Controller
{
    public function index()
    {
        $manhwas = Manhwa::
        latest() // آخرین‌ها اول
        ->paginate(12); // 12 تا در هر صفحه
        return view('admin.manhwas.index', compact('manhwas'));
    }

    // نمایش جزئیات مانهوا
    public function show($id)
    {
        $manhwa = Manhwa::with('chapters')->findOrFail($id);
        return view('admin.manhwas.show', compact('manhwa'));
    }

    public function create()
    {
        return view('admin.manhwas.create');

    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'status' => 'nullable',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'author_id' => 'nullable|exists:users,id',
            'artist_id' => 'nullable|exists:users,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $path = $file->store('image', 'public');
            $data['cover_image'] = $path;
        }

        $manhwa = Manhwa::create($data);

        if (!empty($data['categories'])) {
            $manhwa->categories()->sync($data['categories']);
        }

        return redirect()->route('admin.manhwa.create')
            ->with('success', 'کتاب با موفقیت ایجاد شد.');
    }
    public function edit($id)
    {
        $manhwa = Manhwa::findOrFail($id);
        return view('admin.manhwas.edit', compact('manhwa'));
    }

    public function update(Request $request, $id)
    {
        $manhwa = Manhwa::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'status' => 'nullable',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'author_id' => 'nullable|exists:users,id',
            'artist_id' => 'nullable|exists:users,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id'
        ]);

        // اگر عکس جدید آپلود شد
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $path = $file->store('image', 'public');
            $data['cover_image'] = $path;
        }

        $manhwa->update($data);

        // همگام‌سازی دسته‌بندی‌ها
        $manhwa->categories()->sync($request->categories ?? []);

        return redirect()->route('admin.manhwa.index')
            ->with('success', 'کتاب با موفقیت ویرایش شد.');
    }
    public function destroy($id)
    {
        $manhwa = Manhwa::findOrFail($id);
        $manhwa->delete();
        return redirect()->route('admin.manhwa.index');
    }



    public function show_chapter($id)
    {
        $chapter = Chapter::with('manhua')->findOrFail($id); // ارتباط با مانهوا

        return view('admin.chapters.show', compact('chapter'));
    }
    public function create_chapter(Manhwa $manhwa)
    {
        return view('admin.chapters.create', compact('manhwa'));
    }

    public function store_chapter(Request $request, Manhwa $manhwa)
    {

        $data = $request->validate([
            'chapter_number' => 'required|numeric',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'is_paid'    => 'nullable',
            'image'          => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('chapters', 'public');
        }

        $data['manhwa_id'] = $manhwa->id; // ستون دیتابیس تو مدل Chapter همینه

        Chapter::create($data);

        return redirect()
            ->route('admin.manhwa.show', $manhwa->id)
            ->with('success', 'چپتر جدید با موفقیت اضافه شد.');
    }
    public function edit_chapter($id)
    {
        $chapter = Chapter::findOrFail($id);
        return view('admin.chapters.edit', compact('chapter'));
    }
    public function update_chapter(Request $request, $id)
    {
        $chapter = Chapter::findOrFail($id);

        $data = $request->validate([
            'chapter_number' => 'required|numeric',
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'image'          => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('chapters', 'public');
        }

       $chapter->update($data);

        return redirect()
            ->route('admin.manhwa.show',$id)
            ->with('success', 'چپتر جدید با موفقیت اضافه شد.');
    }
    public function destroy_chapter($id)
    {
        $manhwa = Chapter::findOrFail($id);
        $manhwa->delete();
        return redirect()->route('admin.manhwa.index');
    }

}
