<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AiImageFilterCategory;
use App\Models\AiImageFilter;
use Illuminate\Support\Facades\DB;

class AiImageFilterSeeder extends Seeder
{
    private int $seq = 0;

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        AiImageFilter::truncate();
        AiImageFilterCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // [category_name, [r,g,b], [filter names...]]
        $categories = [
            ['Fantasy',   [160, 80,  220], ['Magic Glow','Fairy Tale','Dream Land','Mystic','Fantasy Blue','Enchanted','Soft Magic','Dream Pop','Fantasy Light','Unreal']],
            ['Film',      [60,  60,  70],  ['Film Grain','Analog Film','Classic Reel','Film Soft','Old Roll','Cinema Film','Film Matte','Grainy Look','Film Fade','Movie Film']],
            ['Dramatic',  [200, 50,  50],  ['High Drama','Bold Shadow','Strong Contrast','Epic Dark','Stage Light','Heavy Mood','Sharp Drama','Power Tone','Dark Drama','Contrast Boost']],
            ['Soft',      [210, 170, 175], ['Soft Glow','Dreamy','Smooth Light','Gentle Tone','Pastel Soft','Soft Fade','Light Touch','Soft Film','Smooth Mood','Creamy Look']],
            ['Vibrant',   [255, 130, 20],  ['Color Punch','Vivid Pop','Bright Boost','High Color','Ultra Pop','Vibrant Glow','Bold Color','Color Burst','Strong Tone','Live Color']],
            ['Minimal',   [170, 170, 170], ['Clean Look','Soft White','Minimal Fade','Simple Tone','Neutral Light','Pure','Grey Soft','Calm White','Minimal Pop','Clear Look']],
            ['Moody',     [60,  60,  100], ['Moody Dark','Deep Mood','Soft Drama','Grey Mood','Dark Soft','Emotional','Shadow Mood','Low Contrast','Mood Fade','Quiet Tone']],
            ['Retro',     [190, 140, 60],  ['Retro Pop','Neon Retro','80s Look','Old School','Retro Wave','Vintage Neon','Classic Pop','Retro Film','Pixel Retro','Retro Bright']],
            ['Cool',      [50,  100, 210], ['Cool Blue','Ice Tone','Frost','Cold Light','Winter Mood','Cool Fade','Blue Chill','Icy Look','Cool Skin','Cold Film']],
            ['Warm',      [230, 140, 50],  ['Warm Skin','Golden Touch','Heat Tone','Amber Glow','Soft Warm','Warm Light','Sun Warm','Cozy Look','Autumn Warm','Warm Balance']],
            ['Artistic',  [80,  160, 90],  ['Oil Canvas','Watercolor Wash','Ink Sketch','Abstract Pop','Fine Brush','Pastel Dream','Bold Strokes','Gallery Glow','Painterly Mood','Creative Splash']],
            ['Nature',    [40,  160, 80],  ['Green Boost','Forest Light','Leafy','Nature Fresh','Earth Tone','Green Mood','Jungle Pop','Outdoor Glow','Natural Soft','Eco Color']],
            ['Urban',     [90,  90,  110], ['City Pop','Street Style','Concrete','Urban Cool','Metro Mood','City Lights','Street Dark','Modern City','Town Vibe','Urban Glow']],
            ['Vintage',   [170, 130, 80],  ['Old Film','Retro Dust','Classic Fade','Vintage Warm','Golden Past','Retro Brown','Old Memory','Analog Look','Film Wash','Vintage Soft']],
            ['Cinematic', [30,  30,  80],  ['Cinema Scope','Movie Night','Drama Frame','Film Contrast','Epic Scene','Wide Screen','Cinema Dark','Movie Tone','Blockbuster','Scene Boost']],
        ];

        foreach ($categories as $si => [$catName, $rgb, $filters]) {
            $thumbDir  = public_path('upload/ai_image_filter/' . $catName . '/thumbnail');
            $thumbFile = $this->makeWebp($thumbDir, strtolower($catName), $rgb, 300, 300);

            $cat = AiImageFilterCategory::create([
                'category_name'  => $catName,
                'category_image' => $thumbFile,
                'sort_order'     => $si + 1,
                'status'         => 1,
            ]);

            foreach ($filters as $fi => $filterName) {
                $shade = [
                    min(255, max(20, $rgb[0] + $fi * 12 - 60)),
                    min(255, max(20, $rgb[1] + $fi * 8  - 40)),
                    min(255, max(20, $rgb[2] + $fi * 6  - 30)),
                ];
                $slug   = str_replace(' ', '_', strtolower($filterName));
                $imgDir = public_path('upload/ai_image_filter/' . $catName . '/images');
                $zipDir = public_path('upload/ai_image_filter/' . $catName . '/zip');

                AiImageFilter::create([
                    'category_id' => $cat->id,
                    'name'        => $filterName,
                    'ai_prompt'   => 'Apply ' . $filterName . ' effect — enhanced ' . strtolower($catName) . ' atmosphere and tones',
                    'image_path'  => $this->makeWebp($imgDir, $slug, $shade, 400, 400),
                    'zip_file'    => $this->makeZip($zipDir, $slug),
                    'sort_order'  => $fi + 1,
                ]);
            }
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
                min(255, max(0, (int)($rgb[0] * (1 - $f * .45) + 10))),
                min(255, max(0, (int)($rgb[1] * (1 - $f * .45) + 10))),
                min(255, max(0, (int)($rgb[2] * (1 - $f * .45) + 10)))
            );
            imageline($img, 0, $y, $w - 1, $y, $c);
        }
        $white = imagecolorallocate($img, 255, 255, 255);
        imagestring($img, 5, (int)(($w - 9) / 2), (int)(($h - 15) / 2), strtoupper(substr($base, 0, 1)), $white);
        imagewebp($img, $dir . DIRECTORY_SEPARATOR . $file, 85);
        imagedestroy($img);
        return $file;
    }

    private function makeZip(string $dir, string $base): string
    {
        if (!is_dir($dir)) mkdir($dir, 0777, true);
        $file = $base . '_' . time() . '_' . (++$this->seq) . '.zip';
        $zip  = new \ZipArchive();
        $zip->open($dir . DIRECTORY_SEPARATOR . $file, \ZipArchive::CREATE);
        $zip->addFromString('info.txt', 'AI Filter Pack: ' . $base);
        $zip->close();
        return $file;
    }
}
