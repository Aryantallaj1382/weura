<?php

// app/Models/Blog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'author',
        'content',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }
    public function getImageAttribute($value)
    {
        return $value ? url("public/$value") : null;

    }
        public function advertisements()
        {
            return $this->morphMany(Advertisement::class, 'advertisable');
        }
}

