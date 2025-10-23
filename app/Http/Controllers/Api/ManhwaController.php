<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\ComingSoonManhua;
use App\Models\Manhwa;
use App\Models\SupportWork;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ManhwaController extends Controller
{
    public function index()
    {

        $latestManhuas = Manhwa::orderBy('created_at', 'desc')->take(3)->get()->map(function ($manhwa) {
            return [
                'id' => $manhwa->id,
                'title' => $manhwa->title,
                'image' => $manhwa->cover_image,
                'created_at' => $manhwa->created_at,
                'episode' => $manhwa?->chapters?->count() ?? 0,
            ];
        });

        $popularManhuas = Manhwa::withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->take(6)
            ->get()->map(function ($manhwa) {
                return [
                    'id' => $manhwa->id,
                    'title' => $manhwa->title,
                    'image' => $manhwa->cover_image,
                    'rating' => $manhwa->rating,
                    'ratings_avg_rating' => $manhwa->ratings_avg_rating,
                    'category' => $manhwa->category,
                ];
            });

        $randomManhuas = Manhwa::inRandomOrder()->take(6)->get();

        $category = Category::all();

        $cacheKey = 'daily_random_manhua_' . now()->toDateString();

        $manhua = Cache::remember($cacheKey, 60 * 24, function () {
            return Manhwa::inRandomOrder()->first();
        });

        $suggested = SystemSetting::where('name','suggested')->value('value');
        $suggested_manhua = Manhwa::where('id' , $suggested)->first();
        $a = [
            'id' => $suggested_manhua->id,
            'title' => $suggested_manhua->title,
            'image' => $suggested_manhua->cover_image,
            'category' => $suggested_manhua->category,
            'rating' => $suggested_manhua->averageRating,
        ];

        $coming_soon = ComingSoonManhua::all();

        $banners = Banner::get()->map(function ($banner) {
            return [
                'id' => $banner->id,
                'title' => $banner->manhuas->title,
                'image' => $banner->manhuas->cover_image,
                'rating' => $banner->manhuas?->averageRating ?? 0,
            ];
        });
        return api_response([
            'banners' => $banners,
            'latest' => $latestManhuas,
            'popular' => $popularManhuas,
            'random' => $randomManhuas,
            'category' => $category,
            'suggested' => $a,
            'coming_soon' => $coming_soon,

        ]);
    }
        public function show($id)
        {
            $manhwa = Manhwa::find($id);
            $similarManhwa = Manhwa::where('id', '!=', $id)
                ->whereHas('categories', function ($query) use ($manhwa) {
                    $query->whereIn('name', $manhwa->categories->pluck('name'));
                })
                ->first(); // فقط یک پیشنهاد می‌خواهیم
            $return = [
                'id' => $manhwa->id,
                'title' => $manhwa->title,
                'image' => $manhwa->cover_image,
                'created_at' => $manhwa->created_at,
                'category' => $manhwa->categories->pluck('name'),
                'rating' => $manhwa->rating,
                'ratings_avg_rating' => $manhwa->ratings_avg_rating,
                'author' => $manhwa->author->name,
                'artist' => $manhwa->artist->name,
                'status' => $manhwa->status,
                'summary' => $manhwa->summary,
                'chapters' => $manhwa->chapters->map(function ($manhwa) {
                    return [
                        'id' => $manhwa->id,
                        'title' => $manhwa->title,
                        'created_at' => $manhwa->created_at->format('Y-m-d'),
                        'is_paid' => $manhwa->is_paid,
                    ];
                }),
                'suggest' => $similarManhwa ? [
                    'id' => $similarManhwa->id,
                    'title' => $similarManhwa->title,
                    'image' => $similarManhwa->cover_image,
                    'ratings' => $similarManhwa->ratings_avg_rating,
                    'category' => $similarManhwa->categories->first()->name,
                ] : null,
                'advertisement' => $manhwa?->advertisements?->first()?->image ?? null,


            ];


            return api_response($return);
        }
        public function chapters($id)
        {
            $chapter = Chapter::findOrFail($id);

            if (auth()->check()) {
                $user = auth()->user();

                auth()->user()->readChapters()->syncWithoutDetaching([$chapter->id]);
                \App\Models\UserActivity::create([
                    'user_id' => $user->id,
                    'description' => "شما چپتر شماره {$chapter->chapter_number} از مانهوای {$chapter->manhua->title} را خواندید.",
                ]);
            }

            return api_response(['image' => $chapter->image]);
        }


    public function toggle($id)
    {
        $user = auth()->user();
        $manhwa = Manhwa::findOrFail($id);

        if ($user->likedManhwas()->where('manhwa_id', $manhwa->id)->exists()) {
            // آنلایک
            $user->likedManhwas()->detach($manhwa->id);
            return api_response(['liked' => false, 'message' => 'آنلایک شد']);
        } else {
            // لایک
            $user->likedManhwas()->attach($manhwa->id);
            return api_response(['liked' => true, 'message' => 'لایک شد']);
        }
    }


    public function supportWithWallet(Request $request)
    {
        $request->validate([
            'manhwa_id' => 'required|exists:manhwas,id',
            'amount'    => 'required|numeric|min:1',
        ]);

        $user = auth()->user();
        $wallet = $user->wallet();
        $balance = $wallet->balance ?? 0;

        if ($balance < $request->amount) {
            return api_response([], 'موجودی کیف پول کافی نیست.');
        }

        // کم کردن مبلغ از کیف پول کاربر
        $wallet->balance -= $request->amount;
        $wallet->save();


        // ثبت حمایت
        SupportWork::create([
            'user_id'   => $user->id,
            'manhwa_id' => $request->manhwa_id,
            'amount'    => $request->amount,
            'type'      => 'wallet',
        ]);

        return api_response([], 'حمایت با موفقیت انجام شد.');
    }
    }
