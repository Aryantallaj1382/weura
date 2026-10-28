<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Manhwa;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{

    public function get_category(Request $request)
    {
        $category = Category::select(['id','name', 'icon' ])->get();
        return api_response($category);

    }
    public function index(Request $request)
    {
        $manhuas = Manhwa::where('admin_status','approved');
        if ($request->has('search')) {
            $manhuas = $manhuas->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->has('status')) {
            $manhuas = $manhuas->where('status', $request->status);
        }
        if ($request->has('looking_id')) {
            $manhuas = $manhuas->where('looking_for_id', $request->looking_id);
        }

        if ($request->has('category')) {
            $categoryId = $request->input('category');
            $manhuas = $manhuas->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            });
        }

        $manhuas->withAvg('ratings', 'rating');

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
               'image' => $item->cover_image_mobile,
               'rating' => $item->averageRating,
           ];
        });
        return api_response($m);

    }
}
