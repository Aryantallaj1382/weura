<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComingSoonManhua extends Model
{
    protected $table = 'coming_soon_manhuas';
    protected $guarded  = [];

    public function getImageAttribute($value)
    {
        return $value ? url("public/$value") : null;

    }
}
