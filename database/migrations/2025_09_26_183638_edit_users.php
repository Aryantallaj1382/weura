<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_new_chapter')->default(true)
                ->comment('اعلان برای منتشر شدن چپتر جدید در کتابخونه');
            $table->boolean('notify_user_referral')->default(true)
                ->comment('اعلان وقتی کاربری کاربر دیگری را معرفی کرد');
            $table->boolean('notify_promotions')->default(true)
                ->comment('اعلان تخفیف‌ها، تبلیغات و آگهی‌های مهم سایت');
            $table->boolean('notify_donation')->default(true)
                ->comment('اعلان دریافت دونیت برای نویسنده');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
