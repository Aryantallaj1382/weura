<?php

// app/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'icon'];

    public function manhuas()
    {
        return $this->belongsToMany(Manhwa::class, 'manhua_category');
    }
    public function getIconAttribute($value)
    {
        return $value ? asset('public/' . ltrim($value, '/')) : null;
    }
}

