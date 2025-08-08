<?php

// app/Models/Ticket.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'user_id',
        'issue_type',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
