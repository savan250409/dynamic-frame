<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StickerCategory;
use Illuminate\Support\Facades\DB;

class StickerSeeder extends Seeder
{
    private int $seq = 0;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        StickerCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // [category_name, [r,g,b], is_premium, [sticker label slugs...]]
        $categories = [
            ['Emoji',    [255, 200, 0],   0, ['happy','sad','love','wink','cool']],
            ['Animals',  [50,  180, 50],  0, ['cat','dog','bear','rabbit','fox']],
            ['Food',     [255, 100, 50],  0, ['pizza','burger','cake','sushi','taco']],
            ['Nature',   [40,  160, 80],  0, ['sun','flower','tree','leaf','mountain']],
            ['Travel',   [50,  100, 200], 0, ['plane','map','camera','passport','luggage']],
            ['Sports',   [200, 50,  50],  1, ['ball','trophy','medal','glove','bike']],
            ['Music',    [100, 50,  200], 1, ['note','guitar','drum','mic','headphone']],
            ['Hearts',   [255, 50,  100], 0, ['red_heart','pink_heart','broken','sparkle','rainbow']],
            ['Stars',    [255, 200, 50],  0, ['gold_star','shooting','sparkle','burst','glow']],
            ['Weather',  [100, 150, 255], 0, ['sun','rain','cloud','snow','rainbow']],
            ['Objects',  [150, 100, 50],  1, ['lamp','book','clock','phone','camera']],
            ['Faces',    [255, 180, 100], 1, ['smile','laugh','wink','cool','angel']],
        ];

        foreach ($categories as $si => [$catName, $rgb, $isPremium, $stickers]) {
            $thumbDir  = public_path('upload/sticker/' . $catName . '/category image');
            $thumbFile = $this->makeWebp($thumbDir, strtolower($catName), $rgb, 300, 300);

            $stickerDir   = public_path('upload/sticker/' . $catName . '/stickers');
            $stickerFiles = [];
            foreach ($stickers as $idx => $label) {
                $shade = [
                    min(255, max(20, $rgb[0] + $idx * 15 - 30)),
                    min(255, max(20, $rgb[1] + $idx * 10 - 20)),
                    min(255, max(20, $rgb[2] + $idx * 8  - 15)),
                ];
                $stickerFiles[] = $this->makeWebp($stickerDir, $label, $shade, 200, 200);
            }

            StickerCategory::create([
                'category_name' => $catName,
                'image'         => $thumbFile,
                'stickers'      => $stickerFiles,
                'is_premium'    => $isPremium,
                'sort_order'    => $si + 1,
                'status'        => 1,
            ]);
        }
    }

    private function makeWebp(string $dir, string $base, array $rgb, int $w = 200, int $h = 200): string
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
