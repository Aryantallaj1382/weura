<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthorRequest extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'digital_painting_skill',
        'writing_skill',
        'sample_file',
        'software',
        'need_support',
    ];

    protected $casts = [
        'need_support' => 'boolean',
        'digital_painting_skill' => 'integer',
        'writing_skill' => 'integer',
    ];
}
