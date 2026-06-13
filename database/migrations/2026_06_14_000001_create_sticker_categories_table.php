<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sticker_categories', function (Blueprint $table) {
            $table->id();
            $table->string('category_name')->unique();
            $table->string('image')->nullable();
            $table->json('stickers')->nullable();
            $table->boolean('is_premium')->default(true);
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sticker_categories');
    }
};
