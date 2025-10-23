<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function readingList(Request $request)
    {
        $user = auth()->user();

        $manhwas = \App\Models\Manhwa::withCount('chapters')
            ->whereHas('chapters.readers', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->paginate(10); // تعداد هر صفحه

        // قالب بندی داده‌ها
        $manhwas->getCollection()->transform(function ($manhwa) use ($user) {
            $readCount = $manhwa->chapters()
                ->whereHas('readers', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->count();

            // فقط مانهواهایی که هنوز کامل نشده
            if ($readCount >= $manhwa->chapters_count) return null;

            return [
                'id'             => $manhwa->id,
                'title'          => $manhwa->title,
                'categories'     => $manhwa->categories?->first()?->name,
                'image'          => $manhwa->cover_image,
                'total_chapters' => $manhwa->chapters_count,
                'read_chapters'  => $readCount,
            ];
        });

        // حذف null ها و ریست collection
        $manhwas->setCollection($manhwas->getCollection()->filter()->values());

        return api_response($manhwas);
    }
    public function completedList(Request $request)
    {
        $user = auth()->user();

        $manhwas = \App\Models\Manhwa::withCount('chapters')
            ->whereHas('chapters.readers', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->paginate(10); // تعداد هر صفحه

        // قالب بندی داده‌ها
        $manhwas->getCollection()->transform(function ($manhwa) use ($user) {
            $readCount = $manhwa->chapters()
                ->whereHas('readers', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->count();

            if ($readCount != $manhwa->chapters_count) return null;

            return [
                'id'             => $manhwa->id,
                'title'          => $manhwa->title,
                'categories'     => $manhwa->categories?->first()?->name,
                'image'          => $manhwa->cover_image,
                'total_chapters' => $manhwa->chapters_count,
                'read_chapters'  => $readCount,
            ];
        });

        // حذف null ها و ریست collection
        $manhwas->setCollection($manhwas->getCollection()->filter()->values());

        return api_response($manhwas);
    }
    public function likedManhwas(Request $request)
    {
        $user = auth()->user();

        $manhwas = $user->likedManhwas()
            ->withCount('chapters') // تعداد چپترها
            ->paginate(10); // هر صفحه 10 تا

        $data = $manhwas->through(function ($manhwa) {
            return [
                'id'             => $manhwa->id,
                'title'           => $manhwa->title,
                'categories'      => $manhwa->categories?->first()?->name,
                'image'           => $manhwa->cover_image,
                'total_chapters'  => $manhwa->chapters_count,
                'created_at'  => $manhwa->created_at,
                'likes_count'    => $manhwa->likes()->count(),
            ];
        });

        return api_response($data);
    }
    public function userAllManhwas(Request $request)
    {
        $user = auth()->user();

        // گرفتن همه مانهواهایی که کاربر خونده یا لایک کرده
        $manhwas = \App\Models\Manhwa::withCount('chapters')
            ->with(['chapters', 'likes', 'categories'])
            ->whereHas('chapters.readers', fn($q) => $q->where('user_id', $user->id))
            ->orWhereHas('likes', fn($q) => $q->where('user_id', $user->id))
            ->get();

        $data = $manhwas->map(function ($manhwa) use ($user) {

            $readCount = $manhwa->chapters->filter(fn($chapter) =>
            $chapter->readers->contains('id', $user->id)
            )->count();

            // تعیین وضعیت
            $status = 'reading';
            if ($readCount == $manhwa->chapters_count) {
                $status = 'completed';
            } elseif ($user->likedManhwas->contains($manhwa)) {
                $status = 'liked';
            }

            return [
                'id'             => $manhwa->id,
                'title'          => $manhwa->title,
                'categories'     => $manhwa->categories?->first()?->name,
                'image'          => $manhwa->cover_image,
                'total_chapters' => $manhwa->chapters_count,
                'read_chapters'  => $readCount,
                'likes_count'    => $manhwa->likes->count(),
                'status'         => $status,
            ];
        });

        return api_response($data);
    }

}
