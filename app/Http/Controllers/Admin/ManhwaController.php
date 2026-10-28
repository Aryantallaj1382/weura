<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Manhwa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use ZipArchive;

class ManhwaController extends Controller
{
    public function approve(Manhwa $manhwa)
    {
        $manhwa->update([
            'admin_status' => 'approved',
            'reject_reason' => null,
        ]);

        return back()->with('success', 'مانهوا تایید شد.');
    }

    public function reject(Request $request, Manhwa $manhwa)
    {
        $request->validate([
            'reject_reason' => 'required|string'
        ]);

        $manhwa->update([
            'admin_status' => 'rejected',
            'reject_reason' => $request->reject_reason,
        ]);

        return back()->with('success', 'مانهوا رد شد.');
    }
    public function index2(Request $request)
    {
        $manhwas = Manhwa::query();

        if ($request->filled('status')) {
            $manhwas->where('admin_status', $request->status);
        }

        $manhwas = $manhwas->latest()->paginate(12);

        return view('admin.manhwas.index2', compact('manhwas'));
    }
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
            'cover_image_mobile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'author_id' => 'required|exists:users,id',
            'artist_id' => 'required|exists:users,id',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/manhua'), $imageName);
            $data['cover_image'] = 'uploads/manhua/' . $imageName;
        }
        if ($request->hasFile('cover_image_mobile')) {
            $image = $request->file('cover_image_mobile');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/manhua'), $imageName);
            $data['cover_image_mobile'] = 'uploads/manhua/' . $imageName;
        }

        $manhwa = Manhwa::create($data);
        $manhwa->update(['admin_status' => 'approved']);

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
            'cover_image_mobile' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'author_id' => 'nullable|exists:users,id',
            'artist_id' => 'nullable|exists:users,id',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id'
        ]);

        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/manhua'), $imageName);
            $data['cover_image'] = 'uploads/manhua/' . $imageName;
        }

        if ($request->hasFile('cover_image_mobile')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/manhua'), $imageName);
            $data['cover_image'] = 'uploads/manhua/' . $imageName;
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
            'is_paid'        => 'nullable',
            'image'          => 'nullable|file|mimes:zip',
        ]);

        if ($request->hasFile('image')) {

            $zipFile = $request->file('image');

            // اسم پوشه یکتا
            $folderName = time() . '_' . uniqid();

            // مسیر ذخیره عکس‌ها
            $destinationPath = public_path('uploads/chapters/' . $folderName);

            // اگر نبود بساز
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // فایل zip موقت
            $zipPath = $zipFile->getRealPath();

            $zip = new ZipArchive();

            if ($zip->open($zipPath) === true) {

                // استخراج فایل‌ها
                $zip->extractTo($destinationPath);
                $zip->close();

                // ذخیره مسیر پوشه
                $data['image'] = 'uploads/chapters/' . $folderName;

            } else {
                return back()->withErrors([
                    'image' => 'فایل ZIP قابل استخراج نیست.'
                ]);
            }
        }

        $data['manhwa_id'] = $manhwa->id;

        Chapter::create($data);

        return redirect()
            ->route('admin.manhwa.show', $manhwa->id)
            ->with('success', 'چپتر جدید با موفقیت اضافه شد.');
    }    public function edit_chapter($id)
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
            'image'          => 'nullable',
        ]);

        if ($request->hasFile('image')) {

            $zipFile = $request->file('image');

            // اسم پوشه یکتا
            $folderName = time() . '_' . uniqid();

            // مسیر ذخیره عکس‌ها
            $destinationPath = public_path('uploads/chapters/' . $folderName);

            // اگر نبود بساز
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // فایل zip موقت
            $zipPath = $zipFile->getRealPath();

            $zip = new ZipArchive();

            if ($zip->open($zipPath) === true) {

                // استخراج فایل‌ها
                $zip->extractTo($destinationPath);
                $zip->close();

                // ذخیره مسیر پوشه
                $data['image'] = 'uploads/chapters/' . $folderName;

            } else {
                return back()->withErrors([
                    'image' => 'فایل ZIP قابل استخراج نیست.'
                ]);
            }
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
