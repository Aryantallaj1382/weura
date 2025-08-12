<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = ['manhwa_id', 'user_id', 'rating'];

    public function manhwa()
    {
        return $this->belongsTo(Manhwa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
