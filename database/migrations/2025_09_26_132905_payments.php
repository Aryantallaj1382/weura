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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable(); // کاربری که پرداخت کرده
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->string('gateway')->default('zarinpal'); // نام درگاه
            $table->string('authority')->nullable(); // authority که زرین‌پال میده
            $table->string('ref_id')->nullable(); // ref_id که بعد از موفقیت زرین‌پال میده
            $table->integer('amount'); // مبلغ تراکنش
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending'); // وضعیت
            $table->timestamps();
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
