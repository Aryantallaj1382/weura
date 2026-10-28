<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OurTeam extends Model
{

    protected $fillable = [
        'name',
        'image',
        'role',
    ];
    public function getImageAttribute($value)
    {
        return $value ? url("public/$value") : null;

    }

}
