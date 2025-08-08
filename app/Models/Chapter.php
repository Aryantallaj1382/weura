<?php

// app/Models/Chapter.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    protected $fillable = [
        'manhua_id', 'chapter_number', 'title', 'description', 'image', 'is_paid'
    ];

    public function manhua()
    {
        return $this->belongsTo(Manhua::class);
    }
}
