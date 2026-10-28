<?php

namespace App\Http\Controllers\Api\Auther;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Manhwa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Morilog\Jalali\Jalalian;

class ManhwaController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();
        $manhwa = Manhwa::where('author_id', $user->id)->latest();
        if ($request->filled('search')) {
            $manhwa->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $manhwa->where('status', $request->status );
        }
        $manhwa = $manhwa->paginate();
        $manhwa->getCollection()->transform(function ($manhwa) {
            return [
                'id' => $manhwa->id,
                'cover_image' => $manhwa->cover_image_mobile,
                'title' => $manhwa->title,
                'admin_status' => $manhwa->admin_status,
                'categories' => $manhwa->category,
                'status' => $manhwa->status,
                'chapter_count' => $manhwa->chapters->count(),
                'updated_at' => $manhwa->updated_at,
            ];
        });
        return api_response($manhwa);

    }
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'title' => 'required',
                'cover_image' => 'required',
                'cover_image_mobile' => 'required',
                'status' => 'required',
                'categories' => 'required|array',
                'categories.*' => 'exists:categories,id',
                'summary' => 'required',
            ],
            [
                'title.required' => 'عنوان الزامی است.',

                'cover_image.required' => 'تصویر کاور الزامی است.',

                'cover_image_mobile.required' => 'تصویر کاور موبایل الزامی است.',

                'status.required' => 'وضعیت الزامی است.',

                'categories.required' => 'انتخاب حداقل یک دسته‌بندی الزامی است.',
                'categories.array' => 'دسته‌بندی‌ها نامعتبر هستند.',
                'categories.*.exists' => 'یکی از دسته‌بندی‌های انتخاب شده معتبر نیست.',

                'summary.required' => 'خلاصه داستان الزامی است.',
            ]
        );


        if ($request->hasFile('cover_image')) {
            $image = $request->file('cover_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/team'), $imageName);
            $imagePath= 'uploads/team/' . $imageName;
        }
        if ($request->hasFile('cover_image_mobile')) {
            $image = $request->file('cover_image_mobile');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/team'), $imageName);
            $imagePath_1= 'uploads/team/' . $imageName;
        }
        $user = auth()->user();

        $manhwa = Manhwa::create([
            'title' => $validated['title'],
            'summary' => $validated['summary'],
            'cover_image' => $imagePath ??  null,
            'cover_image_mobile' => $imagePath_1 ??  null,
            'author_id' => $user->id,
            'artist_id' => $user->id,
            'status' => $validated['status'],
        ]);

        $categories = $validated['categories'];
        if (!empty($categories)) {
            $manhwa->categories()->sync($categories);
        }
        return api_response([],'با موفقیت ذخیره شد  ');

    }
    public function update(Request $request, Manhwa $manhwa)
    {
        $request->validate(
            [
                'title' => 'nullable',
                'summary' => 'nullable',
                'cover_image' => 'nullable|image',
                'cover_image_mobile' => 'nullable|image',
                'categories' => 'required|array',
                'categories.*' => 'exists:categories,id',
            ],
            [
                'cover_image.image' => 'فایل کاور باید یک تصویر معتبر باشد.',

                'cover_image_mobile.image' => 'فایل کاور موبایل باید یک تصویر معتبر باشد.',

                'categories.required' => 'انتخاب حداقل یک دسته‌بندی الزامی است.',
                'categories.array' => 'دسته‌بندی‌ها نامعتبر هستند.',
                'categories.*.exists' => 'یکی از دسته‌بندی‌های انتخاب شده معتبر نیست.',
            ]
        );

        $coverImageToStore = $manhwa->cover_image;
        $coverImageToStore_1 = $manhwa->cover_image_mobile;

        if ($request->hasFile('cover_image')) {
            $destinationPath = public_path('manhwa');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }


            if ($manhwa->cover_image && file_exists(public_path($manhwa->cover_image))) {
                unlink(public_path($manhwa->cover_image));
            }

            $file = $request->file('cover_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);

            $coverImageToStore = 'manhwa/' . $filename;
        }
        if ($request->hasFile('cover_image_mobile')) {
            $destinationPath = public_path('manhwa');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }


            if ($manhwa->cover_image_mobile && file_exists(public_path($manhwa->cover_image_mobile))) {
                unlink(public_path($manhwa->cover_image_mobile));
            }

            $file = $request->file('cover_image_mobile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);

            $coverImageToStore_1 = 'manhwa/' . $filename;
        }

        $data = [
            'title' => $request->title,
            'summary' => $request->summary,
        ];

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $coverImageToStore;
        }

        if ($request->hasFile('cover_image_mobile')) {
            $data['cover_image_mobile'] = $coverImageToStore_1;
        }

        $manhwa->update($data);

        $manhwa->categories()->sync($request->categories);

        return api_response([], 'با موفقیت ویرایش شد');
    }



    public function comments($id)
    {
        $manhwa = Manhwa::findOrFail($id);
        $user = auth()->user();
        $comments = $manhwa->comments()->whereNull('parent_id')->get()->map(function ($comment) use ($user , $manhwa) {
            $is_mine = $user && $comment->user_id == $user->id;
            $is_author = $comment->user_id ==  $manhwa->author_id;
            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'user' => $comment->user->name,
                'profile' => $comment->user->profile,
                'created_at' => Jalalian::fromDateTime($comment->created_at)->ago(),
                'is_mine' => $is_mine,
                'is_author' => $is_author,
                'replies' => $comment->replies()->get()->map(function ($comment) use ($user , $manhwa)  {
                    $is_mine = $user && $comment->user_id == $user->id;
                    $is_author = $comment->user_id ==  $manhwa->author_id;
                    return [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'user' => $comment->user->name,
                        'profile' => $comment->user->profile,
                        'created_at' => Jalalian::fromDateTime($comment->created_at)->ago(),
                        'is_mine' => $is_mine,
                        'is_author' => $is_author,
                    ];
                }),
            ];
        });

        return api_response($comments,'',200,[
            'count' => $comments->count(),
            'name' => $manhwa->title,
            'description' => $manhwa->summary,
            'image' => $manhwa->cover_image_mobile,

            'manhua_id' => $manhwa->id,
            'chapter_count' => $manhwa->chapters->count(),
        ]);


    }


    public function destroy($id)
    {
        $manhwa = Manhwa::find($id);

        if (!$manhwa) {
            return api_response([], 'مانهوا پیدا نشد.', 404);
        }

        // حذف تصویر
        if ($manhwa->cover_image && File::exists(public_path($manhwa->cover_image))) {
            File::delete(public_path($manhwa->cover_image));
        }

        // حذف ارتباط دسته‌بندی‌ها
        $manhwa->categories()->detach();

        // حذف رکورد
        $manhwa->delete();

        return api_response([], 'با موفقیت حذف شد.');
    }
}
