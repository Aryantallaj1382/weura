<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'content',
        'commentable_id',
        'commentable_type',
        'parent_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function commentable()
    {
        return $this->morphTo();
    }
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }
}

