<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('fonts')) {
            return;
        }

        Schema::create('fonts', function (Blueprint $table) {
            $table->id();
            $table->string('font_name')->unique();
            $table->string('font_file')->nullable();
            $table->string('preview_image')->nullable();
            $table->boolean('is_premium')->default(true);
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fonts');
    }
};
