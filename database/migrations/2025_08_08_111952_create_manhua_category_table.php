<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManhuaCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('manhua_category', function(Blueprint $table){
            $table->id();
            $table->foreignId('manhua_id')->constrained('manhuas')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['manhua_id', 'category_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('manhua_category');
    }
}
