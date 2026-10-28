<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\ComingSoonManhua;
use App\Models\Comment;
use App\Models\LookingFor;
use App\Models\Manhwa;
use App\Models\ManhwaLike;
use App\Models\OurTeam;
use App\Models\Rating;
use App\Models\SupportWork;
use App\Models\SystemSetting;
use App\Models\Transaction;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Morilog\Jalali\Jalalian;
use Illuminate\Support\Facades\DB;
use App\Models\ChapterPurchase;
use Illuminate\Support\Facades\Auth;
use function Symfony\Component\Translation\t;

class ManhwaController extends Controller
{
    public function purchase(Chapter $chapter)
    {
        $user = Auth::user();

        if (is_null($chapter->price) || $chapter->price == 0) {
            return api_response([], 'این چپتر رایگان است.', 422);
        }

        if (ChapterPurchase::where('user_id', $user->id)
            ->where('chapter_id', $chapter->id)
            ->exists()) {
            return api_response([], 'این چپتر قبلاً خریداری شده است.', 422);
        }

        if ($user->wallet->balance < $chapter->price) {
            return api_response([], 'موجودی کیف پول کافی نیست.', 422);
        }

        DB::transaction(function () use ($user, $chapter) {

            $user->wallet()->decrement('balance', $chapter->price);
            ChapterPurchase::create([
                'user_id'    => $user->id,
                'chapter_id' => $chapter->id,
                'price'      => $chapter->price,
            ]);
        });

        return api_response([], 'خرید با موفقیت انجام شد.');
    }

    public function donate(Request $request)
    {
        $request->validate([
            'coming_soon_id' => ['required', 'exists:coming_soon_manhuas,id'],
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        $user = auth()->user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'message' => 'کیف پول یافت نشد.'
            ], 404);
        }
        $amount = $request->amount * 1000;
        if ($wallet->balance < $amount) {
            return response()->json([
                'message' => 'موجودی کیف پول کافی نیست.'
            ], 422);
        }

        DB::transaction(function () use ($request, $wallet , $amount) {

            $comic = ComingSoonManhua::lockForUpdate()
                ->findOrFail($request->coming_soon_id);

            $wallet->decrement('balance', (int)$amount);

            $comic->increment('price', $amount);
        });
      Transaction::create([
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'operation_type' => 'withdrawal',
            'status' => 'successful'
        ]);

        return response()->json([
            'message' => 'دونیت با موفقیت انجام شد.'
        ]);
    }

    public function rate(Request $request,$id)
    {
        $rating_value = $request->rate;
        $manhwa = Manhwa::find($id);
        $user = auth()->user();
        if (!$user) {
            return api_response([],'لطفا ابتدا وارد شوید.',401);
        }
        $rate = Rating::where('user_id', $user->id)->where('manhwa_id', $manhwa->id)->first();
        if ($rate) {
            $rate->update(['rating' => $rating_value]);
            return   api_response([], 'امتیاز با موفقیت به‌روزرسانی شد');
        }
        Rating::create([
            'user_id' => $user->id,
            'rating' => $rating_value ,
            'manhwa_id' => $manhwa->id,
        ]);
        return api_response([], 'امتیاز ثبت شد');

    }


//    public function rate(Request $request,$id)
//    {
//        $rate = $request->rate;
//        $manhwa = Manhwa::find($id);
//        $user = auth()->user();
//        if (!$user) {
//            return api_response([],'login pls');
//        }
//        $rate = Rating::where('user_id', $user->id)->where('manhwa_id', $manhwa->id)->first();
//        if ($rate) {
//            $rate->update(['rating' => $rate]);
//         return   api_response([], 'امتیاز ثبت شد');
//        }
//       Rating::create([
//            'user_id' => $user->id,
//            'rating' => $rate,
//            'manhwa_id' => $manhwa->id,
//        ]);
//        return api_response([], 'امتیاز ثبت شد');
//
//    }


    public function search(Request $request)
    {
        $search = $request->search;
        $man = Manhwa::where('title', 'like', '%' . $search . '%')->paginate();
        $man->getCollection()->transform(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
            ];
        });
        return api_response($man);

    }

    public function index()
    {

        $latestManhuas = Manhwa::where('admin_status','approved')->orderBy('created_at', 'desc')->take(3)->get()->map(function ($manhwa) {
            return [
                'id' => $manhwa->id,
                'title' => $manhwa->title,
                'image' => $manhwa->cover_image_mobile,
                'created_at' => $manhwa->created_at,
                'episode' => $manhwa?->chapters?->count() ?? 0,
                'episode_name' => $manhwa?->chapters?->last()->title ?? 0,
            ];
        });

        $popularManhuas = Manhwa::where('admin_status','approved')->withAvg('ratings', 'rating')
            ->orderByDesc('ratings_avg_rating')
            ->take(6)
            ->get()->map(function ($manhwa) {
                return [
                    'id' => $manhwa->id,
                    'title' => $manhwa->title,
                    'image' => $manhwa->cover_image_mobile,
                    'rating' => $manhwa->average_rating,
                    'category' => $manhwa->category,
                ];
            });

        $randomManhuas = Manhwa::where('admin_status','approved')->inRandomOrder()->take(6)->get()->map(function ($manhwa) {
            return [
                'id' => $manhwa->id,
                'title' => $manhwa->title,
                'image' => $manhwa->cover_image_mobile,
                'created_at' => $manhwa->created_at,
                'artist' => $manhwa->artist->name,
                'author' => $manhwa->author->name,
            ];
        });

        $category = Category::all();

        $cacheKey = 'daily_random_manhua_' . now()->toDateString();

        $manhua = Cache::remember($cacheKey, 60 * 24, function () {
            return Manhwa::inRandomOrder()->first();
        });

        $suggested = SystemSetting::where('name', 'suggested')->value('value');
        $suggested_manhua = Manhwa::where('id', $suggested)->first();
        $suggested_manhua ? $a = [
            'id' => $suggested_manhua?->id,
            'title' => $suggested_manhua?->title,
            'image' => $suggested_manhua?->cover_image_mobile,
            'category' => $suggested_manhua?->category,
            'rating' => $suggested_manhua?->averageRating,
        ]:[];

        $coming_soon = ComingSoonManhua::get()->map(function ($coming_soon) {
            return [
                'id' => $coming_soon->id,
                'title' => $coming_soon->title,
                'image' => $coming_soon->image,
                'category' => $coming_soon->category,
                'description' => $coming_soon->description ,
                'created_at' => Jalalian::fromDateTime($coming_soon->created_at)->format('M d'),
            ];
        });;

        $banners = Banner::get()->map(function ($banner) {
            return [
                'id' => $banner->manhuas->id,
                'title' => $banner->manhuas->title,
                'image' => $banner->manhuas->cover_image_mobile,
                'rating' => number_format(
                    (float) ($banner->manhuas?->averageRating ?? 0),
                    1
                ),            ];
        });
        $looking = LookingFor::get()->map(function ($banner) {
            return [
                'id' => $banner->id,
                'title' => $banner->title,
                'logo' => $banner->logo,

            ];
        });
        $team= OurTeam::get()->map(function ($banner) {
            return [
                'id' => $banner->id,
                'name' => $banner->name,
                'image' => $banner->image,
                'role' => $banner?->role ,
                ];
        });

        $aaa = [
          [
              'comment' => 'عالی هستید',
              'name' => 'amir'
          ],
            [
              'comment' => 'بی نظیر هستید',
                'name' => 'ali'
          ],
            [
              'comment' => 'خیلی خوبه سایتتون',
                'name' => 'aryan'
          ],
        ];
        return api_response([
            'banners' => $banners,
            'latest' => $latestManhuas,
            'popular' => $popularManhuas,
            'random' => $randomManhuas,
            'category' => $category,
            'suggested' => $a ?? null,
            'coming_soon' => $coming_soon,
            'our_team' => $team,
            'comment' => $aaa,
            'looking' => $looking,

        ]);
    }
    public function show($id)
    {

        $manhwa = Manhwa::find($id);

        $total_comment = Comment::where('commentable_type',Manhwa::class)->where('commentable_id', $id)->count();
        $total_rate = Rating::where('manhwa_id', $id)->count();

        $user = auth()->user();
        $liked = null;
        if ($user)
        {
            $liked = ManhwaLike::where('manhwa_id', $manhwa->id)->where('user_id', $user->id)->first();

        }
        $user = auth()->user();


        if (auth()->check()) {
            $user = auth()->user();

            UserActivity::create([
                'user_id' => $user->id,
                'description' => "شما مانهوای  {$manhwa->title}  را مشاهده کردید.",
            ]);
        }
        $purchasedChapters = [];

        if ($user) {
            $purchasedChapters = \App\Models\ChapterPurchase::where('user_id', $user->id)
                ->pluck('chapter_id')
                ->toArray();
        }


        if (!$manhwa) {
            return api_response([],'چیزی یافت نشد',404);
        }
        $similarManhwa = Manhwa::where('id', '!=', $id)
            ->whereHas('categories', function ($query) use ($manhwa) {
                $query->whereIn('name', $manhwa?->categories?->pluck('name'));
            })
            ->take(3)->get(); // فقط یک پیشنهاد می‌خواهیم
        $return = [

            'id' => $manhwa->id,
            'total_comment' => $total_comment,
            'total_rate' => $total_rate,
            'title' => $manhwa->title,
            'liked' => $liked ? true : false,
            'image' => $manhwa->cover_image,
            'image_mobile' => $manhwa->cover_image_mobile,
            'created_at' => $manhwa->created_at,
            'category' => $manhwa->categories->pluck('name'),
            'category_id' => $manhwa->categories->pluck('id'),
            'rating' => $manhwa->user_rating ?$manhwa->user_rating->rating: 0,
            'ratings_avg_rating' => $manhwa->average_rating ? (float) number_format($manhwa->average_rating, 1) : 0,
            'author' => $manhwa->author->name,
            'artist' => $manhwa->artist->name,
            'status' => $manhwa->status,
            'summary' => $manhwa->summary,
            'chapters' => $manhwa->chapters->map(function ($chapter) use ($purchasedChapters) {
                return [
                    'id' => $chapter->id,
                    'chapter_number' => $chapter->chapter_number,
                    'title' => $chapter->title,
                    'created_at' => $chapter->created_at->format('Y-m-d'),
                    'price' => $chapter->price,

                    'is_purchased' => is_null($chapter->price)
                        ? true
                        : in_array($chapter->id, $purchasedChapters),
                ];
            }),
            'suggest' => $similarManhwa->map(function ($manhwa) {
                return [
                    'id' => $manhwa->id,
                    'title' => $manhwa->title,
                    'image' => $manhwa->cover_image_mobile,
                    'ratings' => $manhwa->ratings_avg_rating,
                    'category' => $manhwa->categories?->first()->name,
                ];
            }),
            'advertisement' => $manhwa?->advertisements?->first()?->image ?? null,


        ];


        return api_response($return);
    }


    public function chapters($id)
    {
        $chapter = Chapter::findOrFail($id);

        if (auth()->check()) {
            $user = auth()->user();

            $user->readChapters()->syncWithoutDetaching([$chapter->id]);

            UserActivity::create([
                'user_id' => $user->id,
                'description' => "شما چپتر شماره {$chapter->chapter_number} از مانهوای {$chapter->manhua->title} را خواندید.",
            ]);
        }

        $previousChapter = Chapter::where('manhwa_id', $chapter->manhwa_id)
            ->where('chapter_number', '<', $chapter->chapter_number)
            ->orderByDesc('chapter_number')
            ->first();

        $nextChapter = Chapter::where('manhwa_id', $chapter->manhwa_id)
            ->where('chapter_number', '>', $chapter->chapter_number)
            ->orderBy('chapter_number')
            ->first();

        if (!is_null($chapter->price) && $chapter->price > 0) {

            if (!auth()->check()) {
                return api_response([
                    'image' => [],
                    'advertisement' => $chapter?->advertisements?->first()?->image,
                    'price' => $chapter?->price,
                    'is_purchased' => false,
                    'previous_chapter_id' => $previousChapter?->id,
                    'next_chapter_id' => $nextChapter?->id,
                ], 'ابتدا وارد حساب کاربری خود شوید.', 401);
            }

            $isPurchased = ChapterPurchase::where('user_id', auth()->id())
                ->where('chapter_id', $chapter->id)
                ->exists();

            if (!$isPurchased) {
                return api_response([
                    'image' => [],
                    'advertisement' => $chapter?->advertisements?->first()?->image,
                    'price' => $chapter?->price,
                    'is_purchased' => false,
                    'previous_chapter_id' => $previousChapter?->id,
                    'next_chapter_id' => $nextChapter?->id,
                ], 'برای مشاهده این چپتر ابتدا باید آن را خریداری کنید.', 403);
            }
        }

        return api_response([
            'image' => $chapter->images,
            'manhua_id' => $chapter->manhwa_id,
            'is_purchased' => true,
            'advertisement' => $chapter?->advertisements?->first()?->image,
            'previous_chapter_id' => $previousChapter?->id,
            'next_chapter_id' => $nextChapter?->id,
        ]);
    }
    public function toggle($id)
    {
        $user = auth()->user();
        $manhwa = Manhwa::findOrFail($id);

        if ($user->likedManhwas()->where('manhwa_id', $manhwa->id)->exists()) {
            $user->likedManhwas()->detach($manhwa->id);
            return api_response(['liked' => false, 'message' => 'آنلایک شد']);
        } else {
            $user->likedManhwas()->attach($manhwa->id);
            return api_response(['liked' => true, 'message' => 'لایک شد']);
        }
    }


    public function supportWithWallet(Request $request)
    {
        $request->validate([
            'manhwa_id' => 'required|exists:manhwas,id',
            'amount' => 'required|numeric|min:1000',
        ]);
        $user = auth()->user();
        if(!$user)
        {
            return api_response([],'',401);
        }
        $wallet = $user->wallet()->firstOrCreate(
            [],
            ['balance' => 0]
        );
        $balance = $wallet->balance ?? 0;

        if ($balance < $request->amount) {
            return api_response([], 'موجودی کیف پول کافی نیست.',422);
        }

        $wallet->balance -= $request->amount;
        $wallet->save();


        SupportWork::create([
            'user_id' => $user->id,
            'manhwa_id' => $request->manhwa_id,
            'amount' => $request->amount,
            'type' => 'wallet',
        ]);

        return api_response([], 'حمایت با موفقیت انجام شد.');
    }
    public function comments($id)
    {
        $manhwa = Manhwa::findOrFail($id);
        $user = auth()->user();
        $comments = $manhwa->comments()->whereNull('parent_id')->get()->map(function ($comment) use ($user , $manhwa) {
            $is_mine = $user && $comment->user_id == $user->id;
            $is_author = $comment->user_id ==  $manhwa->author_id;

            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'user' => $comment->user->name,
                'created_at' => Jalalian::fromDateTime($comment->created_at)->ago(),
                'is_mine' => $is_mine,
                'is_author' => $is_author,
                'replies' => $comment->replies()->get()->map(function ($comment) use ($user , $manhwa)  {
                    $is_mine = $user && $comment->user_id == $user->id;
                    $is_author = $comment->user_id ==  $manhwa->author_id;
                    return [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'user' => $comment->user->name,
                        'created_at' => Jalalian::fromDateTime($comment->created_at)->ago(),

                        'is_mine' => $is_mine,
                        'is_author' => $is_author,

                    ];
                }),


            ];
        });

        return api_response($comments);


    }
    public function store_comment(Request $request)
    {
        $request->validate([
            'text' => 'required',
            'parent_id' => 'nullable',
            'manhwa_id' => 'required|exists:manhwas,id',
        ]);
        $user = auth()->user();
        $comment = Comment::create([
           'user_id' => $user->id,
           'commentable_id' => $request->manhwa_id,
           'commentable_type' => Manhwa::class,
           'content' => $request->text,
            'parent_id' => $request->parent_id,

        ]);
        return api_response([],'کامنت با موفقیت ثبت شد');

    }
    public function delete_comment($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return api_response([],'not found');
        }
        $user = auth()->user();
        if ($comment->user_id == $user->id) {
            $comment->delete();
            return api_response([],'کامنت با موفقیت حذف شد');
        }
        return api_response([],'کامنت متعلق به شما نیست');
    }
    public function edit_comment(Request $request, $id)
    {
        $request->validate([
            'text' => 'required',
        ]);
        $comment = Comment::find($id);
        if (!$comment) {
            return api_response([],'not found');
        }
        $user = auth()->user();
        if ($comment->user_id == $user->id) {
            $comment->update([
                'content' => $request->text,
            ]);
            return api_response([],'کامنت با موفقیت حذف شد');
        }
        return api_response([],'کامنت متعلق به شما نیست');

    }
}
