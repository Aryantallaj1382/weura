<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportWork extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'manhwa_id',
        'amount',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function manhwa()
    {
        return $this->belongsTo(\App\Models\Manhwa::class);
    }
}
