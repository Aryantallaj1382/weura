<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Manhwa;
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
        return api_response([
            'latest' => $latestManhuas,
            'popular' => $popularManhuas,
            'random' => $randomManhuas,
            'category' => $category,
        ]);
    }
}
