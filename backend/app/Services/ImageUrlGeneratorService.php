<?php

namespace App\Services;

use App\Interfaces\ImageUrlGeneratorServiceInterface;
use Illuminate\Support\Facades\Storage;

class ImageUrlGeneratorService implements ImageUrlGeneratorServiceInterface
{
    public function getUrl(string $fileName): string
    {
        return Storage::disk('images')->url($fileName);
    }

    public function getThumbnailsUrls(string $filename, array $sizes): array
    {
        $thumbnails = [];
        foreach ($sizes as $size) {
            $thumbnailPath = $size . '/' . $filename;
            if (Storage::disk('thumbnails')->exists($thumbnailPath)) {
                $thumbnails[$size] = Storage::disk('thumbnails')->url($thumbnailPath);
            }
        }

        return $thumbnails;
    }
}
