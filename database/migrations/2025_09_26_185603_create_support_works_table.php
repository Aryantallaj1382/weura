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
        Schema::create('support_works', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');   // یوزر حمایت کننده
            $table->foreignId('manhwa_id')->constrained('manhwas')->onDelete('cascade'); // مانهوا مورد حمایت
            $table->bigInteger('amount'); // مبلغ حمایت
            $table->enum('type', ['wallet', 'gateway'])->nullable(); // نوع پرداخت: کیف پول یا درگاه
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_works');
    }
};
