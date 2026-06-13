<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddZipFileToAiImageFiltersTable extends Migration
{
    public function up()
    {
        Schema::table('ai_image_filters', function (Blueprint $table) {
            $table->string('zip_file')->nullable()->after('image_path');
        });
    }

    public function down()
    {
        Schema::table('ai_image_filters', function (Blueprint $table) {
            $table->dropColumn('zip_file');
        });
    }
}
