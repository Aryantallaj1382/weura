<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    protected $fillable = ['title', 'content', 'image'];

    public function advertisable()
    {
        return $this->morphTo();
    }
    public function getImageAttribute($value)
    {
        return $value ? url("public/$value") : null;
    }
}

