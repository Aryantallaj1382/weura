<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $blogs = $blogs->paginate();
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
}
