<?php

namespace App\Http\Controllers\Api\Panel;

use App\Models\Chapter;
use App\Models\Manhwa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Morilog\Jalali\Jalalian;

class DashboardController extends Collection
{
    public function dashboard(Request $request)
    {
        $user = auth()->user();
        // ----------------- 3 مانهوا لایک شده -----------------
        $liked = $user->likedManhwas()->where('is_delete', 0)
            ->withCount('chapters')
            ->take(3)
            ->get()
            ->map(fn($manhwa) => [
                'id'             => $manhwa->id,
                'title'          => $manhwa->title,
                'image'          => $manhwa->cover_image_mobile,
                'date'          => Jalalian::fromDateTime($manhwa->created_at)->ago(),

            ]);

        // ----------------- 3 مانهوا در حال خوندن -----------------
        $reading = \App\Models\Manhwa::withCount('chapters')
            ->whereHas('chapters.readers', fn ($q) => $q->where('user_id', $user->id))
            ->get()
//            ->filter(function ($manhwa) use ($user) {
//                $readCount = $manhwa->chapters()
//                    ->whereHas('readers', fn ($q) => $q->where('user_id', $user->id))
//                    ->count();
//
//                return $readCount < $manhwa->chapters_count;
//            })
            ->map(function ($manhwa) use ($user) {
                $readCount = $manhwa->chapters()
                    ->whereHas('readers', fn ($q) => $q->where('user_id', $user->id))
                    ->count();

                return (object) [
                    'id'             => $manhwa->id,
                    'title'          => $manhwa->title,
                    'categories'     => $manhwa->categories?->first()?->name,
                    'image'          => $manhwa->cover_image_mobile,
                    'total_chapters' => $manhwa->chapters_count,
                    'read_chapters'  => $readCount,
                ];
            })
            ->first();


        // ----------------- آخرین مانهوا در حال خوندن -----------------

        $lastReading = \App\Models\Manhwa::whereHas('chapters.readers', fn($q) => $q->where('user_id', $user->id))
            ->get()
//            ->filter(function($manhwa) use ($user) {
//                $readCount = $manhwa->chapters()
//                    ->whereHas('readers', fn($q) => $q->where('user_id', $user->id))
//                    ->count();
//                return $readCount < $manhwa->chapters_count;
//            })
            ->sortByDesc(function($manhwa) use ($user) {
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
            ->last();


        $mostLiked = Manhwa::withCount('likes')
            ->orderByDesc('likes_count')
            ->first();

        $mostLikedData = $mostLiked ? [
            'id'             => $mostLiked->id,
            'title'          => $mostLiked->title,
            'categories'     => $mostLiked->categories?->first()?->name,
            'image'          => $mostLiked->cover_image_mobile,
            'description'          => $mostLiked->summary,
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
                'image'          => $manhwa->cover_image_mobile,
                'total_chapters' => $manhwa->chapters_count,
            ]);


        $activities = \App\Models\UserActivity::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($activity) => [
                'id'          => $activity->id,
                'description' => $activity->description,
                'date'          => Jalalian::fromDateTime($activity->created_at)->ago(),
            ]);
        // ----------------- خروجی نهایی -----------------
        return api_response([
            'liked'        => $liked,
            'last_reading'      => $reading,
            'mostLikedData' => $mostLikedData,
            'recommended'   => $recommended,
            'activities'    => $activities,
        ]);
    }


}

