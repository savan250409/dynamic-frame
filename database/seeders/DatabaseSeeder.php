<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            AiImageFilterSeeder::class,
            FilterSeeder::class,
            StickerSeeder::class,
            DoodleSeeder::class,
            FontSeeder::class,
        ]);
    }
}
