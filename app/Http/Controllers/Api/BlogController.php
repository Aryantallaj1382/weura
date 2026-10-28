<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;
use App\Models\Manhwa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Morilog\Jalali\Jalalian;

class BlogController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/blogs",
     *     summary="لیست بلاگ‌ها به همراه دسته‌بندی",
     *     tags={"بلاگ"},
     *     @OA\Response(
     *         response=200,
     *         description="لیست بلاگ‌ها",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=123),
     *                 @OA\Property(property="title", type="string", example="عنوان بلاگ"),
     *                 @OA\Property(property="image", type="string", example="http://example.com/image.jpg"),
     *                 @OA\Property(property="category", type="string", example="دسته‌بندی نمونه"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2025-08-08T20:00:00Z")
     *             )
     *         )
     *     )
     * )
     */
    public function category()
    {
        $category = BlogCategory::all();
        return api_response($category);

    }
    public function index(Request $request)
    {
        $blogs = Blog::with('category');
        if ($request->search) {
            $blogs = $blogs->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->category) {
            $blogs = $blogs->where('category_id', $request->category);
        }
        $blogs = $blogs->paginate(9);
        $blogs->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'content' => Str::limit($item->content, 30),
                'title' => $item->title,
                'author' => $item->author,
                'image' => $item->image,
                'category' => $item->category->name,
                'created_at' => $item->created_at,
            ];
        });

        return api_response($blogs);
    }
    public function popular()
    {
        $blogs = Blog::with('category')->orderBy('view', 'desc')->take(4)->get();
        $a = $blogs->map(function ($item) {
            return [
                'id' => $item->id,
                'image' => $item->image,
                'title' => $item->title,
            ];
        });
        return api_response($a);

    }




    public function show($id)
    {
        $blog = Blog::with('category')->findOrFail($id);
        $blog->increment('view');
        $return = [
            'id' => $blog->id,
            'title' => $blog->title,
            'content' => $blog->content,
            'image' => $blog->image,
            'category' => $blog->category->name,
            'created_at' => $blog->created_at,
            'author' => $blog->author,
            'advertisement' => $blog?->advertisements?->first()?->image ?? null,
        ];
        return api_response($return);
    }



    public function comments($id)
    {
        $manhwa = Blog::findOrFail($id);
        $user = auth()->user();
        $comments = $manhwa->comments()->whereNull('parent_id')->get()->map(function ($comment) use ($user , $manhwa) {
            $is_mine = $user && $comment->user_id == $user->id;
            $is_author = $comment->user_id ==  $manhwa->author_id;

            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'user' => $comment->user->name,
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
                        'created_at' => Jalalian::fromDateTime($comment->created_at)->ago(),
                        'is_mine' => $is_mine,
                        'is_author' => $is_author,

                    ];
                }),


            ];
        });

        return api_response($comments);


    }
    public function store_comment(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'parent_id' => 'nullable',
            'manhwa_id' => 'required|exists:manhwas,id',
        ]);
        $user = auth()->user();
        $comment = Comment::create([
            'user_id' => $user->id,
            'commentable_id' => $request->manhwa_id,
            'commentable_type' => Blog::class,
            'content' => $request->text,
            'parent_id' => $request->parent_id,

        ]);
        return api_response([],'کامنت با موفقیت ثبت شد');

    }
    public function delete_comment($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return api_response([],'not found');
        }
        $user = auth()->user();
        if ($comment->user_id == $user->id) {
            $comment->delete();
            return api_response([],'کامنت با موفقیت حذف شد');
        }
        return api_response([],'کامنت متعلق به شما نیست');
    }
    public function edit_comment(Request $request, $id)
    {
        $request->validate([
            'text' => 'required',
        ]);
        $comment = Comment::find($id);
        if (!$comment) {
            return api_response([],'not found');
        }
        $user = auth()->user();
        if ($comment->user_id == $user->id) {
            $comment->update([
                'content' => $request->text,
            ]);
            return api_response([],'کامنت با موفقیت حذف شد');
        }
        return api_response([],'کامنت متعلق به شما نیست');

    }
}
