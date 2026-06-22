<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageService
{
    /**
     * Compress and save an image using native PHP GD library.
     *
     * @param UploadedFile $file
     * @param string $directory Directory inside storage/app/public/
     * @param int $quality Compression quality (0-100)
     * @return string Relative path of the saved file (e.g. 'reports/xyz.jpg')
     */
    public static function compressAndSave(UploadedFile $file, string $directory, int $quality = 60): string
    {
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
            $extension = 'jpg';
        }
        
        $filename = uniqid() . '.' . $extension;
        $tempPath = $file->getRealPath();
        
        // Ensure directory exists in public storage
        $fullDir = storage_path('app/public/' . $directory);
        if (!file_exists($fullDir)) {
            mkdir($fullDir, 0755, true);
        }
        
        $destination = $fullDir . '/' . $filename;
        
        $info = getimagesize($tempPath);
        $mime = $info['mime'] ?? '';
        
        // Process based on mime type
        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $image = imagecreatefromjpeg($tempPath);
            if ($image) {
                imagejpeg($image, $destination, $quality);
                imagedestroy($image);
            } else {
                $file->move($fullDir, $filename);
            }
        } elseif ($mime === 'image/png') {
            $image = imagecreatefrompng($tempPath);
            if ($image) {
                imagealphablending($image, false);
                imagesavealpha($image, true);
                // For imagepng, quality parameter is 0 (no compression) to 9 (max compression)
                $pngQuality = (int) round((100 - $quality) / 10);
                $pngQuality = max(0, min(9, $pngQuality));
                imagepng($image, $destination, $pngQuality);
                imagedestroy($image);
            } else {
                $file->move($fullDir, $filename);
            }
        } else {
            // Fallback for other formats (like gif)
            $file->move($fullDir, $filename);
        }
        
        return $directory . '/' . $filename;
    }

    /**
     * Compress an existing file on disk in-place.
     *
     * @param string $absolutePath Absolute path to the file
     * @param int $quality Compression quality (0-100)
     * @return void
     */
    public static function compressFileInPlace(string $absolutePath, int $quality = 60): void
    {
        if (!file_exists($absolutePath)) {
            return;
        }

        $info = getimagesize($absolutePath);
        $mime = $info['mime'] ?? '';

        if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
            $image = @imagecreatefromjpeg($absolutePath);
            if ($image) {
                @imagejpeg($image, $absolutePath, $quality);
                @imagedestroy($image);
            }
        } elseif ($mime === 'image/png') {
            $image = @imagecreatefrompng($absolutePath);
            if ($image) {
                @imagealphablending($image, false);
                @imagesavealpha($image, true);
                $pngQuality = (int) round((100 - $quality) / 10);
                $pngQuality = max(0, min(9, $pngQuality));
                @imagepng($image, $absolutePath, $pngQuality);
                @imagedestroy($image);
            }
        }
    }
}
