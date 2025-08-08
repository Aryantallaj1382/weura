<?php

// 1. create_manhua_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManhuaTable extends Migration
{
    public function up()
    {
        Schema::create('manhuas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('status', ['completed', 'ongoing', 'hiatus'])->default('ongoing');
            $table->text('summary')->nullable();
            $table->string('cover_image')->nullable();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('artist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('manhuas');
    }
}

