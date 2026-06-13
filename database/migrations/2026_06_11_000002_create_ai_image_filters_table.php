<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiImageFiltersTable extends Migration
{
    public function up()
    {
        Schema::create('ai_image_filters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->text('ai_prompt');
            $table->string('image_path');
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->foreign('category_id')
                  ->references('id')
                  ->on('ai_image_filter_categories')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ai_image_filters');
    }
}
