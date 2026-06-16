<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddInputCountToAiImageFiltersTable extends Migration
{
    public function up()
    {
        Schema::table('ai_image_filters', function (Blueprint $table) {
            $table->integer('input_count')->default(1)->after('zip_file');
        });

        // Make name and ai_prompt nullable without doctrine/dbal
        DB::statement('ALTER TABLE ai_image_filters MODIFY name VARCHAR(255) NULL');
        DB::statement('ALTER TABLE ai_image_filters MODIFY ai_prompt TEXT NULL');
    }

    public function down()
    {
        Schema::table('ai_image_filters', function (Blueprint $table) {
            $table->dropColumn('input_count');
        });
    }
}
