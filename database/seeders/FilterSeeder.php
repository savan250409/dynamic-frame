<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FilterCategory;
use App\Models\Filter;
use Illuminate\Support\Facades\DB;

class FilterSeeder extends Seeder
{
    private int $seq = 0;

    /** RGB palettes per category name (case-insensitive key) */
    private array $palettes = [
        'artistic'  => [80,  160, 90],
        'sunny'     => [240, 190, 20],
        'ocean'     => [50,  130, 200],
        'dark'      => [50,  50,  60],
        'vintage'   => [170, 130, 80],
        'cinematic' => [30,  30,  80],
        'portrait'  => [220, 160, 120],
        'nature'    => [40,  160, 80],
        'urban'     => [90,  90,  110],
        'night'     => [30,  30,  80],
        'warm'      => [230, 140, 50],
        'cool'      => [50,  100, 210],
        'retro'     => [190, 140, 60],
        'moody'     => [60,  60,  100],
        'minimal'   => [170, 170, 170],
        'vibrant'   => [255, 80,  20],
        'soft'      => [210, 170, 175],
        'dramatic'  => [200, 50,  50],
        'film'      => [60,  60,  70],
        'fantasy'   => [160, 80,  220],
    ];

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Filter::truncate();
        FilterCategory::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $csvPath = base_path('pro_filter_categories_20x20 (1)(Filters).csv');

        if (!file_exists($csvPath)) {
            $this->command->warn('CSV file not found: ' . $csvPath);
            return;
        }

        $handle = fopen($csvPath, 'r');
        $headers = null;
        $rows    = [];

        while (($row = fgetcsv($handle)) !== false) {
            if ($headers === null) {
                // normalise header names
                $headers = array_map(fn($h) => strtolower(trim($h)), $row);
                continue;
            }
            if (empty(array_filter($row))) continue;   // skip blank CSV rows

            $data = array_combine($headers, $row);
            $cat  = trim($data['category']    ?? $data['Category']    ?? '');
            $name = trim($data['filter name'] ?? $data['filter_name'] ?? $data['name'] ?? '');
            if ($cat === '' || $name === '') continue;

            $rows[] = [
                'category'   => $cat,
                'name'       => $name,
                'saturation' => floatval($data['saturation'] ?? 1),
                'brightness' => floatval($data['brightness'] ?? 0),
                'contrast'   => floatval($data['contrast']   ?? 1),
                'red'        => floatval($data['red']        ?? 1),
                'green'      => floatval($data['green']      ?? 1),
                'blue'       => floatval($data['blue']       ?? 1),
                'type'       => strtolower(trim($data['type'] ?? 'free')),
            ];
        }
        fclose($handle);

        // Group by category preserving CSV order
        $grouped = [];
        foreach ($rows as $row) {
            $grouped[$row['category']][] = $row;
        }

        $catSort = 1;
        foreach ($grouped as $catName => $filters) {
            $rgb     = $this->palettes[strtolower($catName)] ?? [120, 120, 120];
            $imgDir  = public_path('upload/filter/' . $catName . '/category image');
            $imgFile = $this->makeWebp($imgDir, strtolower($catName), $rgb, 300, 300);

            $category = FilterCategory::create([
                'name'       => $catName,
                'image'      => $imgFile,
                'sort_order' => $catSort++,
                'status'     => 1,
            ]);

            foreach ($filters as $fi => $f) {
                Filter::create([
                    'filter_category_id' => $category->id,
                    'name'               => $f['name'],
                    'saturation'         => $f['saturation'],
                    'brightness'         => $f['brightness'],
                    'contrast'           => $f['contrast'],
                    'red'                => $f['red'],
                    'green'              => $f['green'],
                    'blue'               => $f['blue'],
                    'is_premium'         => ($f['type'] === 'pro') ? 1 : 0,
                    'sort_order'         => $fi + 1,
                    'status'             => 1,
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
}
