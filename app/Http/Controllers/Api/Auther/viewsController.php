<?php

namespace App\Http\Controllers\Api\Auther;

use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Manhwa;
use App\Models\SupportWork;
use App\Models\User;
use Morilog\Jalali\Jalalian;


class viewsController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $man  = Manhwa::where('author_id', $user->id)->count();
        $chap  = Chapter::whereRelation('manhua','author_id', $user->id)->count();
        $amount = SupportWork::whereRelation('manhwa','author_id', $user->id)->sum('amount');
        $view = Manhwa::where('author_id', $user->id)->sum('view');

        $books = Manhwa::where('author_id', $user->id)->latest()->paginate(10);
        $books->getCollection()->transform(function ($item, $key) {
            $amount = SupportWork::whereRelation('manhwa','manhwa_id', $item->id)->sum('amount');
            $lastChapter = $item->chapters()->latest('created_at')->first();

            return [
                'id' => $item->id,
                'name' => $item->name,
                'view' => $item->view,
                'chapter' => $item->chapters()->count(),
                'last_upload' => $lastChapter
                    ? Jalalian::fromDateTime($lastChapter->created_at)->format('Y/m/d   ')
                    : null,
                'amount' => $amount,
            ];
        });

        return api_response([
            'manhwa' => $man,
            'chap' => $chap,
            'view' => $view,
            'amount' => $amount,
            'books' => $books,
        ]);

    }

}
