<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Manhwa;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    public function index(Request $request)
    {
        $manhuas = Manhwa::query();
        if ($request->has('search')) {
            $manhuas = $manhuas->where('title', 'like', '%' . $request->search . '%');
        }
        $manhuas->withAvg('ratings', 'rating');

        // 🧭 مرتب‌سازی (sort)
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'latest':
                    $manhuas->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $manhuas->orderBy('created_at', 'asc');
                    break;
                case 'rating_high':
                    $manhuas->orderBy('ratings_avg_rating', 'desc'); // 👈 بر اساس ستون محاسبه‌شده
                    break;
                case 'rating_low':
                    $manhuas->orderBy('ratings_avg_rating', 'asc');
                    break;
                default:
                    $manhuas->orderBy('created_at', 'desc');
            }
        } else {
            $manhuas->orderBy('created_at', 'desc');
        }
        $m = $manhuas->paginate(12);
        $m->getCollection()->transform(function ($item) {
           return [
               'id' => $item->id,
               'title' => $item->title,
               'image' => $item->cover_image,
               'rating' => $item->averageRating,
           ];
        });
        return api_response($m);

    }
}
