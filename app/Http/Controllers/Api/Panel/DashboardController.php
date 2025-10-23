<?php

namespace App\Http\Controllers\Api\Panel;

use App\Models\Chapter;
use App\Models\Manhwa;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class DashboardController extends Collection
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();

        // ----------------- 3 مانهوا لایک شده -----------------
        $liked = $user->likedManhwas()
            ->withCount('chapters')
            ->take(3)
            ->get()
            ->map(fn($manhwa) => [
                'id'             => $manhwa->id,
                'title'          => $manhwa->title,
                'image'          => $manhwa->cover_image,

            ]);

        // ----------------- 3 مانهوا در حال خوندن -----------------
        $reading = \App\Models\Manhwa::withCount('chapters')
            ->whereHas('chapters.readers', fn($q) => $q->where('user_id', $user->id))
            ->get()
            ->filter(function($manhwa) use ($user) {
                $readCount = $manhwa->chapters()
                    ->whereHas('readers', fn($q) => $q->where('user_id', $user->id))
                    ->count();
                return $readCount < $manhwa->chapters_count;
            })
            ->take(3)
            ->map(function($manhwa) use ($user) {
                $readCount = $manhwa->chapters()
                    ->whereHas('readers', fn($q) => $q->where('user_id', $user->id))
                    ->count();
                return [
                    'id'             => $manhwa->id,
                    'title'          => $manhwa->title,
                    'categories'     => $manhwa->categories?->first()?->name,
                    'image'          => $manhwa->cover_image,
                    'total_chapters' => $manhwa->chapters_count,
                    'read_chapters'  => $readCount,
                ];
            });

        // ----------------- آخرین مانهوا در حال خوندن -----------------

        $lastReading = \App\Models\Manhwa::whereHas('chapters.readers', fn($q) => $q->where('user_id', $user->id))
            ->get()
            ->filter(function($manhwa) use ($user) {
                $readCount = $manhwa->chapters()
                    ->whereHas('readers', fn($q) => $q->where('user_id', $user->id))
                    ->count();
                return $readCount < $manhwa->chapters_count;
            })
            ->sortByDesc(function($manhwa) use ($user) {
                // پیدا کردن آخرین چپتر خونده شده از جدول واسط user_chapters
                $lastChapter = Chapter::where('manhwa_id', $manhwa->id)
                    ->whereHas('readers', fn($q) => $q->where('user_id', $user->id))
                    ->orderByDesc(
                        Chapter::select('user_chapters.created_at')
                            ->join('user_chapters', 'chapters.id', '=', 'user_chapters.chapter_id')
                            ->whereColumn('chapters.id', 'chapters.id')
                            ->where('user_chapters.user_id', $user->id)
                            ->limit(1)
                    )
                    ->first();

                return $lastChapter?->userChaptersPivot?->created_at ?? now();
            })
            ->first();

        $lastReadingData = $lastReading ? [
            'id'             => $lastReading->id,
            'title'          => $lastReading->title,
            'categories'     => $lastReading->categories?->first()?->name,
            'image'          => $lastReading->cover_image,
            'total_chapters' => $lastReading->chapters_count,
            'read_chapters'  => $lastReading->chapters()
                ->whereHas('readers', fn($q) => $q->where('user_id', $user->id))
                ->count(),
        ] : null;

        $mostLiked = Manhwa::withCount('likes')
            ->orderByDesc('likes_count')
            ->first();

        $mostLikedData = $mostLiked ? [
            'id'             => $mostLiked->id,
            'title'          => $mostLiked->title,
            'categories'     => $mostLiked->categories?->first()?->name,
            'image'          => $mostLiked->cover_image,
            'total_chapters' => $mostLiked->chapters_count,
            'likes_count'    => $mostLiked->likes_count,
        ] : null;
        $recommended = \App\Models\Manhwa::latest()
            ->take(4)
            ->get()
            ->map(fn($manhwa) => [
                'id'             => $manhwa->id,
                'title'          => $manhwa->title,
                'categories'     => $manhwa->categories?->first()?->name,
                'image'          => $manhwa->cover_image,
                'total_chapters' => $manhwa->chapters_count,
            ]);


        $activities = \App\Models\UserActivity::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->take(6) // آخرین ۶ فعالیت
            ->get()
            ->map(fn($activity) => [
                'id'          => $activity->id,
                'description' => $activity->description,
                'created_at'  => $activity->created_at->toDateTimeString(),
            ]);
        // ----------------- خروجی نهایی -----------------
        return api_response([
            'liked'        => $liked,
            'reading'      => $reading,
            'last_reading' => $lastReadingData,
            'mostLikedData' => $mostLikedData,
            'recommended'   => $recommended,
            'activities'    => $activities,
        ]);
    }


}
