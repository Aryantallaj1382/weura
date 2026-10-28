<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LookingFor extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',

        'logo',
    ];

    public function manhwas()
    {
        return $this->hasMany(Manhwa::class);
    }
    public function getLogoAttribute($value)
    {
        return $value ? asset('public/' . ltrim($value, '/')) : null;
    }
}
