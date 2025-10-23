<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('author_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // نام
            $table->string('phone'); // تلفن
            $table->string('digital_painting_skill')->comment('0-100 میزان تسلط به نقاشی دیجیتال');
            $table->string('writing_skill')->comment('0-100 میزان تسلط به نویسندگی');
            $table->string('sample_file')->nullable()->comment('نمونه کار (فایل)');
            $table->string('software')->nullable()->comment('نرم افزار مورد استفاده برای کشیدن نقاشی داستان');
            $table->string('need_support')->default(false)->comment('آیا نیاز به پشتیبانی و کمک دارید؟');
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('author_requests');
    }
};
