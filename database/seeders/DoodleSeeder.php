<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doodle;
use Illuminate\Support\Facades\DB;

class DoodleSeeder extends Seeder
{
    private int $seq = 0;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Doodle::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // [name, doodle_type, is_premium, [r,g,b]]
        $doodles = [
            ['Simple Line',      'line',  0, [100, 100, 100]],
            ['Dash Line',        'line',  0, [120, 120, 130]],
            ['Shadow Line',      'line',  0, [50,  50,  80]],
            ['Shadow Dash Line', 'line',  0, [60,  60,  90]],
            ['Rainbow Line',     'line',  0, [200, 80,  160]],
            ['Star',             'image', 0, [255, 200, 0]],
            ['Flowers',          'image', 0, [255, 100, 150]],
            ['Butterfly',        'image', 0, [100, 50,  200]],
            ['Heart',            'image', 0, [255, 50,  80]],
            ['Smile Flower',     'image', 0, [255, 190, 0]],
            ['Watermelon',       'image', 1, [80,  180, 80]],
            ['Mango',            'image', 1, [255, 150, 0]],
            ['Snow',             'image', 0, [180, 220, 255]],
            ['Cloud',            'image', 0, [150, 200, 255]],
            ['Love',             'image', 0, [255, 80,  120]],
            ['Teddy',            'image', 1, [180, 130, 80]],
            ['Music',            'image', 1, [80,  80,  200]],
        ];

        foreach ($doodles as $si => [$name, $type, $isPremium, $rgb]) {
            $slug   = strtolower(str_replace(' ', '_', $name));
            $imgDir = public_path('upload/doodle/' . $name);
            $file   = $this->makeWebp($imgDir, $slug, $rgb, 300, 300);

            Doodle::create([
                'name'        => $name,
                'image'       => $file,
                'doodle_type' => $type,
                'is_premium'  => $isPremium,
                'sort_order'  => $si + 1,
                'status'      => 1,
            ]);
        }
    }

    private function makeWebp(string $dir, string $base, array $rgb, int $w = 300, int $h = 300): string
    {
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $file = $base . '_' . time() . '_' . (++$this->seq) . '.webp';
        $img  = imagecreatetruecolor($w, $h);
        for ($y = 0; $y < $h; $y++) {
            $f = $y / $h;
            $c = imagecolorallocate($img,
                min(255, max(0, (int)($rgb[0] * (1 - $f * .4) + 10))),
                min(255, max(0, (int)($rgb[1] * (1 - $f * .4) + 10))),
                min(255, max(0, (int)($rgb[2] * (1 - $f * .4) + 10)))
            );
            imageline($img, 0, $y, $w - 1, $y, $c);
        }
        $white = imagecolorallocate($img, 255, 255, 255);
        imagestring($img, 5, (int)(($w - 9) / 2), (int)(($h - 15) / 2), strtoupper(substr($base, 0, 1)), $white);
        imagewebp($img, $dir . DIRECTORY_SEPARATOR . $file, 85);
        imagedestroy($img);
        return $file;
    }
}
