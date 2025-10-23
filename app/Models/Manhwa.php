<?php

// app/Models/Manhwa.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manhwa extends Model
{
    protected $fillable = [
        'title', 'status', 'summary', 'cover_image', 'author_id', 'artist_id' ,'view'
    ];

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

    public function getCoverImageAttribute($value)
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



}
