<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;

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
    public function index()
    {
        $blogs = Blog::with('category')->get();

        $blogs->transform(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'image' => $item->image,
                'category' => $item->category->name,
                'created_at' => $item->created_at,
            ];
        });

        return api_response($blogs);
    }




    public function show($id)
    {
        $blog = Blog::with('category')->findOrFail($id);
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
