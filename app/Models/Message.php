<?php

// app/Models/Message.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'ticket_id',
        'message_text',
        'sender_type',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
