<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManhuaCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('manhwa_category', function(Blueprint $table){
            $table->id();
            $table->foreignId('manhwa_id')->constrained('manhwas')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['manhwa_id', 'category_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('manhwa_category');
    }
}
