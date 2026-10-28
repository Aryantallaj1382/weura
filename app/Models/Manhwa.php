<?php

// app/Models/Manhwa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manhwa extends Model
{
    protected $guarded = [];
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function advertisements()
    {
        return $this->morphMany(Advertisement::class, 'advertisable');
    }

    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'manhwa_category');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }

    // app/Models/Manhwa.php

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function getAverageRatingAttribute()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }
    public function getUserRatingAttribute()
    {
        $user = auth()->user();
        if ($user) {
            return $this->ratings()->where('user_id', $user->id)->first('rating') ?? 0;

        }
        return null;
    }

    public function getCoverImageAttribute($value)
    {
        return $value ? asset('public/' . ltrim($value, '/')) : null;
    }
    public function getCoverImageMobileAttribute($value)
    {
        return $value ? asset('public/' . ltrim($value, '/')) : null;
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'manhwa_likes')
            ->withTimestamps();
    }
    public function getCategoryAttribute()
    {
        return $this->categories->first()?->name;
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function lookingFor()
    {
        return $this->belongsTo(LookingFor::class);
    }

}
