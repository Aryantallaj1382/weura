<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\ComingSoonManhua;
use App\Models\Manhwa;
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

        $coming_soon = ComingSoonManhua::all();

        $banners = Banner::get()->map(function ($banner) {
            return [
                'id' => $banner->id,
                'title' => $banner->manhuas->title,
                'image' => $banner->manhuas->cover_image,
                'rating' => $banner->manhuas?->rating ?? 0,
            ];
        });
        return api_response([
            'banners' => $banners,
            'latest' => $latestManhuas,
            'popular' => $popularManhuas,
            'random' => $randomManhuas,
            'category' => $category,
            'suggested' => $suggested ?url("public/$suggested"):null,
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
            $chapters = Chapter::find($id);
            return api_response(['image'=>$chapters->image]);

        }
    }
