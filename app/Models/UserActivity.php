<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'description',
    ];

    // رابطه با کاربر
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected static function booted()
    {
        static::creating(function ($activity) {
            // تعداد رکوردهای موجود کاربر
            $count = self::where('user_id', $activity->user_id)->count();

            if ($count >= 6) {
                // حذف قدیمی‌ترین رکورد
                $oldest = self::where('user_id', $activity->user_id)
                    ->orderBy('created_at')
                    ->first();
                if ($oldest) {
                    $oldest->delete();
                }
            }
        });
    }
}
