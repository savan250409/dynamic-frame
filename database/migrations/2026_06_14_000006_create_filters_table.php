<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('filters')) {
            return;
        }

        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filter_category_id')->constrained('filter_categories')->onDelete('cascade');
            $table->string('name');
            $table->float('saturation')->default(1);
            $table->float('brightness')->default(0);
            $table->float('contrast')->default(1);
            $table->float('red')->default(1);
            $table->float('green')->default(1);
            $table->float('blue')->default(1);
            $table->boolean('is_premium')->default(true);
            $table->integer('sort_order')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('filters');
    }
};
