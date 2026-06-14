<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class BundleAssets extends Command
{
    protected $signature   = 'assets:bundle';
    protected $description = 'Concatenate all CSS and JS assets into single bundle files for faster page load';

    private array $cssOrder = [
        'assets/css/bootstrap.min.css',
        'assets/css/plugins.min.css',
        'assets/css/kaiadmin.min.css',
        'assets/css/fonts.min.css',
    ];

    private array $jsOrder = [
        'assets/js/core/jquery-3.7.1.min.js',
        'assets/js/core/popper.min.js',
        'assets/js/core/bootstrap.min.js',
        'assets/js/kaiadmin.min.js',
    ];

    public function handle(): int
    {
        $this->bundleCSS();
        $this->bundleJS();

        $cssKb = round(filesize(public_path('assets/css/bundle.min.css')) / 1024, 1);
        $jsKb  = round(filesize(public_path('assets/js/bundle.min.js'))  / 1024, 1);

        $this->info('Assets bundled successfully.');
        $this->line("  CSS → public/assets/css/bundle.min.css  ({$cssKb} KB)");
        $this->line("  JS  → public/assets/js/bundle.min.js   ({$jsKb} KB)");

        return self::SUCCESS;
    }

    private function bundleCSS(): void
    {
        $out = '';
        foreach ($this->cssOrder as $rel) {
            $path = public_path($rel);
            if (!file_exists($path)) {
                $this->warn("  [CSS] not found: $rel");
                continue;
            }
            $out .= file_get_contents($path) . "\n";
        }
        file_put_contents(public_path('assets/css/bundle.min.css'), $out);
    }

    private function bundleJS(): void
    {
        $out = '';
        foreach ($this->jsOrder as $rel) {
            $path = public_path($rel);
            if (!file_exists($path)) {
                $this->warn("  [JS] not found: $rel");
                continue;
            }
            // Ensure each file ends with ; to prevent parse errors when concatenated
            $content = rtrim(file_get_contents($path));
            if (!str_ends_with($content, ';') && !str_ends_with($content, '}')) {
                $content .= ';';
            }
            $out .= $content . "\n";
        }
        file_put_contents(public_path('assets/js/bundle.min.js'), $out);
    }
}
