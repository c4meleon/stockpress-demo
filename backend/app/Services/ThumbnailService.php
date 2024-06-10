<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ThumbnailServiceInterface;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class ThumbnailService implements ThumbnailServiceInterface
{
    /**
     * @throws \Exception
     */
    public function generate(string $fileName, string $filePath, string $size): array
    {
        list($width, $height) = explode('x', $size);
        $thumbnail = Image::read($filePath)->resize((int)$width, (int)$height);

        $thumbnailPath = $size . '/' . $fileName;
        // TODO typehint $thumbnail
        /** @var TYPE_NAME $thumbnail */
        if (!Storage::disk('thumbnails')->put($thumbnailPath, (string) $thumbnail->encode())) {
            throw new \Exception('Failed to store the thumbnail.');
        }

        return [$size => Storage::disk('thumbnails')->path( $thumbnailPath)];
    }
}
