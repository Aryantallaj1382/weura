<?php

// app/Models/Chapter.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
        'manhwa_id', 'chapter_number', 'title', 'description', 'image', 'is_paid'
    ];

    public function manhua()
    {
        return $this->belongsTo(Manhwa::class , 'manhwa_id');
    }
    public function getImageAttribute($value)
    {
        return $value? url("public/$value") : null;

    }
    public function readers()
    {
        return $this->belongsToMany(User::class, 'user_chapters')->withTimestamps();
    }

}
