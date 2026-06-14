<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Font;
use Illuminate\Support\Facades\DB;

class FontSeeder extends Seeder
{
    private int $seq = 0;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Font::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // [font_name, is_premium, [r,g,b]]
        $fonts = [
            ['Roboto',            0, [60,  120, 200]],
            ['Open Sans',         0, [50,  180, 100]],
            ['Lato',              0, [180, 100, 50]],
            ['Montserrat',        0, [100, 50,  200]],
            ['Oswald',            0, [200, 80,  50]],
            ['Raleway',           1, [50,  150, 200]],
            ['Nunito',            0, [255, 150, 50]],
            ['Playfair Display',  1, [130, 80,  50]],
            ['Merriweather',      0, [80,  130, 80]],
            ['Poppins',           0, [200, 50,  100]],
            ['Ubuntu',            1, [200, 100, 50]],
            ['Source Sans Pro',   0, [60,  60,  160]],
        ];

        foreach ($fonts as $si => [$fontName, $isPremium, $rgb]) {
            $slug    = strtolower(str_replace(' ', '_', $fontName));
            $fontDir = public_path('upload/font/' . $fontName);
            if (!is_dir($fontDir)) mkdir($fontDir, 0777, true);

            $ttfFile     = $this->makeTtf($fontDir, $slug);
            $previewFile = $this->makeWebp($fontDir, $slug . '_preview', $rgb, 400, 120);

            Font::create([
                'font_name'     => $fontName,
                'font_file'     => $ttfFile,
                'preview_image' => $previewFile,
                'is_premium'    => $isPremium,
                'sort_order'    => $si + 1,
                'status'        => 1,
            ]);
        }
    }

    private function makeTtf(string $dir, string $base): string
    {
        $file = $base . '_' . time() . '_' . (++$this->seq) . '.ttf';
        // Minimal fake TTF header + padding (placeholder only — not a valid font)
        $content = "\x00\x01\x00\x00\x00\x0A\x00\x80\x00\x03\x00\x40" . str_repeat("\x00", 1024);
        file_put_contents($dir . DIRECTORY_SEPARATOR . $file, $content);
        return $file;
    }

    private function makeWebp(string $dir, string $base, array $rgb, int $w = 400, int $h = 120): string
    {
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $file = $base . '_' . time() . '_' . (++$this->seq) . '.webp';
        $img  = imagecreatetruecolor($w, $h);
        for ($y = 0; $y < $h; $y++) {
            $f = $y / $h;
            $c = imagecolorallocate($img,
                min(255, max(0, (int)($rgb[0] * (1 - $f * .35) + 10))),
                min(255, max(0, (int)($rgb[1] * (1 - $f * .35) + 10))),
                min(255, max(0, (int)($rgb[2] * (1 - $f * .35) + 10)))
            );
            imageline($img, 0, $y, $w - 1, $y, $c);
        }
        $white = imagecolorallocate($img, 255, 255, 255);
        // Write "Aa" as font preview placeholder (font-5 chars are 9px wide each)
        imagestring($img, 5, (int)(($w / 2) - 9), (int)(($h - 15) / 2), 'Aa', $white);
        imagewebp($img, $dir . DIRECTORY_SEPARATOR . $file, 85);
        imagedestroy($img);
        return $file;
    }
}
