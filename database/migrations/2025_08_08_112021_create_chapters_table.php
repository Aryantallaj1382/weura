<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChaptersTable extends Migration
{
    public function up()
    {
        Schema::create('chapters', function(Blueprint $table){
            $table->id();
            $table->foreignId('manhua_id')->constrained('manhuas')->onDelete('cascade');
            $table->integer('chapter_number');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->timestamps();

            $table->unique(['manhua_id', 'chapter_number']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('chapters');
    }
}
