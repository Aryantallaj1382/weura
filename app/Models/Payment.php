<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'transaction_id',
        'gateway',
        'authority',
        'ref_id',
        'amount',
        'status',
    ];



    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    /*
     * آیا پرداخت موفق بوده؟
     */
    public function isSuccessful()
    {
        return $this->status === 'success';
    }

    /*
     * آیا پرداخت در حالت انتظار است؟
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /*
     * آیا پرداخت ناموفق بوده؟
     */
    public function isFailed()
    {
        return $this->status === 'failed';
    }
}
