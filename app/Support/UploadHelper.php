<?php

namespace App\Support;

class UploadHelper
{
    public static function uniqueName(string $directory, string $filename): string
    {
        $directory = rtrim($directory, '/\\');
        if (!file_exists($directory . DIRECTORY_SEPARATOR . $filename)) {
            return $filename;
        }

        $info = pathinfo($filename);
        $base = $info['filename'] ?? $filename;
        $ext  = isset($info['extension']) ? '.' . $info['extension'] : '';

        $candidate = $base . '_' . time() . $ext;
        $i = 1;
        while (file_exists($directory . DIRECTORY_SEPARATOR . $candidate)) {
            $candidate = $base . '_' . time() . '_' . $i . $ext;
            $i++;
        }
        return $candidate;
    }
}
