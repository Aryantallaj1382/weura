<?php

namespace App\Http\Controllers\Api\Panel;

use App\Http\Controllers\Controller;
use App\Models\Manhwa;
use App\Models\ManhwaLike;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Morilog\Jalali\Jalalian;

class LibraryController extends Controller
{
    public function deleteOrLikedManhwa(Request $request , $id)
    {
        $manhwa = Manhwa::find($id);
        $user = auth()->user();
        $liked = ManhwaLike::where('manhwa_id', $manhwa->id)->where('user_id', $user->id)->first();
        if (!$liked) {
            ManhwaLike::create([
                'manhwa_id' => $manhwa->id,
                'user_id' => $user->id,
            ]);
            return api_response([],'مانهوا ذخیره شد');

        }
        elseif ($liked && $liked->is_delete == 0) {
            $liked->update([
                'is_delete' => 1,
            ]);
            return api_response([],'مانهوا حذف شد');

        }
        else{
            $liked->update([
                'is_delete' => 0,
            ]);
            return api_response([],'مانهوا ریکاوری شد');

        }



    }
    public function readingList(Request $request)
    {
        $user = auth()->user();
        $manhwas = Manhwa::withCount('chapters')
            ->whereHas('chapters.readers', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->paginate(10);
        $manhwas->getCollection()->transform(function ($manhwa) use ($user) {
            $readCount = $manhwa->chapters()
                ->whereHas('readers', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })
                ->count();
            if ($readCount >= $manhwa->chapters_count) return null;
            return [
                'id'             => $manhwa->id,
                'title'          => $manhwa->title,
                'categories'     => $manhwa->categories?->first()?->name,
                'image'          => $manhwa->cover_image_mobile,
                'status'          => 'reading',
                'total_chapters' => $manhwa->chapters_count,
                'read_chapters'  => $readCount,
            ];
        });

        $manhwas->setCollection($manhwas->getCollection()->filter()->values());

        return api_response($manhwas);
    }
    public function completedList(Request $request)
    {
        $user = auth()->user();

        $manhwas = Manhwa::withCount('chapters')
            ->whereHas('chapters.readers', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->paginate(10);
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
                'image'          => $manhwa->cover_image_mobile,
                'status'          => 'completed',
                'total_chapters' => $manhwa->chapters_count,
                'read_chapters'  => $readCount,
            ];
        });

        $manhwas->setCollection($manhwas->getCollection()->filter()->values());

        return api_response($manhwas);
    }
    public function likedManhwas(Request $request)
    {
        $user = auth()->user();

        $manhwas = $user->likedManhwas()
            ->where('is_delete',0)
            ->withCount('chapters') // تعداد چپترها
            ->paginate(10); // هر صفحه 10 تا

        $data = $manhwas->through(function ($manhwa) {
            return [
                'id'             => $manhwa->id,
                'title'           => $manhwa->title,
                'categories'      => $manhwa->categories?->first()?->name,
                'image'           => $manhwa->cover_image_mobile,
                'status'           => 'saved',
                'total_chapters'  => $manhwa->chapters_count,
                'likes_count'    => $manhwa->likes()->count(),
                'liked_at'       => Jalalian::fromDateTime($manhwa->created_at)->format('m/d'),

            ];
        });

        return api_response($data);
    }

    public function deleteManhwas(Request $request)
    {
        $user = auth()->user();
        $user = User::find(15);

        $manhwas = $user->likedManhwas()
            ->where('is_delete', 1)
            ->withCount('chapters')
            ->with('categories')
            ->paginate(10);

        $now = Carbon::now();

        $data = $manhwas->through(function ($manhwa) use ($now, $user) {
            $likedAt = $manhwa->pivot?->updated_at ?? $manhwa->pivot?->created_at;

            if (!$likedAt) {
                return null;
            }

            $daysPassed = $now->diffInDays(Carbon::parse($likedAt));
            if ($daysPassed >= 30) {
                $user->likedManhwas()->detach($manhwa->id);
                return null;
            }

            $daysLeft = 30 - $daysPassed;

            return [
                'id'              => $manhwa->id,
                'title'           => $manhwa->title,
                'categories'      => $manhwa->categories?->first()?->name,
                'image'           => $manhwa->cover_image_mobile,
                'status'           => 'deleted',
                'total_chapters'  => $manhwa->chapters_count,
                'likes_count'     => $manhwa->likes()->count(),
                'liked_at'        => $likedAt->toDateTimeString(),
                'days_left'       => (int)$daysLeft, // ⏳ تعداد روز باقی‌مانده تا حذف
            ];
        })->filter()->values(); // حذف آیتم‌های null بعد از detach

        return api_response($data);
    }

    public function userAllManhwas(Request $request)
    {
        $user = auth()->user();

        // گرفتن همه مانهواهایی که کاربر خونده یا لایک کرده
        $manhwas = Manhwa::withCount('chapters')
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
                'image'          => $manhwa->cover_image_mobile,
                'total_chapters' => $manhwa->chapters_count,
                'read_chapters'  => $readCount,
                'likes_count'    => $manhwa->likes->count(),
                'status'         => $status,
            ];
        });

        return api_response($data);
    }

}
