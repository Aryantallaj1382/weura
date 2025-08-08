<?php

// app/Models/Manhua.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Manhua extends Model
{
    protected $fillable = [
        'title', 'status', 'summary', 'cover_image', 'author_id', 'artist_id'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'manhua_category');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class);
    }
}
