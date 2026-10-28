<?php

namespace App\Http\Controllers\Api\Panel;

use App\Helpers\zarinpal;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Manhwa;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Morilog\Jalali\Jalalian;

class ProfileController extends Controller
{
    public function test()
    {
        $paymentService = new zarinpal();
        $callback ='https://api.weura.ir';
        return $paymentService->pay(1, 1000 , $callback);

    }
    public function index()
    {
        $user = auth()->user();

        return api_response([
            'name'                 => $user->name,
            'email'                => $user->email,
            'user_name'                => $user->user_name,
            'role'                => $user->role=='author' ? 'author' : 'profile',
            'profile'              => $user->profile,
            'notify_new_chapter'    => $user->notify_new_chapter,
            'notify_user_referral'  => $user->notify_user_referral,
            'notify_promotions'     => $user->notify_promotions,
            'notify_donation'       => $user->notify_donation,
        ]);
    }


    public function profile(Request $request)
    {
        $request->validate([
            'name'                  => 'nullable|string|max:255',
            'user_name'                  => 'nullable|string|max:255',
            'email'                 => 'nullable|email|max:255',
            'profile'               => 'nullable|file', // عکس پروفایل
            'notify_new_chapter'    => 'nullable',
            'notify_user_referral'  => 'nullable',
            'notify_promotions'     => 'nullable',
            'notify_donation'       => 'nullable',
        ]);

        $user = auth()->user();
        if (\App\Models\User::where('user_name', $request->user_name)
            ->where('id', '!=', $user->id)
            ->exists()) {

            return api_response([], 'نام کاربری قبلاً انتخاب شده است.', 422);
        }
        if ($request->hasFile('profile')) {
            $file = $request->file('profile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('profiles');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            $file->move($destinationPath, $filename);
            $user->profile = 'profiles/' . $filename;
        }
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('user_name')) $user->user_name = $request->user_name;
        if ($request->has('notify_new_chapter')) $user->notify_new_chapter = $request->notify_new_chapter;
        if ($request->has('notify_user_referral')) $user->notify_user_referral = $request->notify_user_referral;
        if ($request->has('notify_promotions')) $user->notify_promotions = $request->notify_promotions;
        if ($request->has('notify_donation')) $user->notify_donation = $request->notify_donation;

        $user->save();

        return api_response([], 'با موفقیت آپدیت شد');
    }

    public function changePassword(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'old_password' => 'required',
            'password' => 'required',
        ]);
        if (Hash::check($request->old_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
            return api_response([], 'رمز تغییر کرد');
        }

        return api_response([],'رمز قدیمی اشتباه است',422);
    }
    public function comments()
    {
        $user = auth()->user();
        if(!$user)
        {
            return api_response([],'');
        }
        $comments = Comment::where('commentable_type' , Manhwa::class)->whereNull('parent_id')->where('user_id', $user->id)->orderBy('commentable_id')->paginate();
        $comments->getCollection()->transform(function ($comment) use ($user) {
            $is_mine = $user && $comment->user_id == $user->id;

            return [
                'id' => $comment->id,
                'content' => $comment->content,
                'created_at' => Jalalian::fromDateTime($comment->created_at)->ago(),
                'is_mine' => $is_mine,
                'replies' => $comment->replies()->get()->map(function ($comment) use ($user )  {
                    $is_mine = $user && $comment->user_id == $user->id;
                    return [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'user' => $comment->user->name,
                        'profile' => $comment->user->profile,
                        'created_at' => Jalalian::fromDateTime($comment->created_at)->ago(),
                        'is_mine' => $is_mine,
                    ];
                }),
                'count' => $comment->count(),
                'name' => $comment->commentable?->title,
                'manhua_id' => $comment->commentable?->id,
                'description' => $comment->commentable?->summary,
                'image' => $comment->commentable?->cover_image,
                'chapter_count' => $comment->commentable?->chapters->count(),

            ];
        });

        return api_response($comments,'',200,[

        ]);


    }

}
