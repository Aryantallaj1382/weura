<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('looking_fors', function (Blueprint $table) {
            $table->id();


            $table->string('title');

            $table->string('logo');

            $table->timestamps();

        });

        Schema::table('manhwas', function (Blueprint $table) {

            $table->foreignId('looking_for_id')
                ->nullable()
                ->after('id')
                ->constrained('looking_fors')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('looking_fors');
    }
};
