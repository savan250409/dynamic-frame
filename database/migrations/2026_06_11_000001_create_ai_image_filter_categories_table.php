<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiImageFilterCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('ai_image_filter_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name')->unique();
            $table->string('category_image')->nullable();
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_image_filter_categories');
    }
}
