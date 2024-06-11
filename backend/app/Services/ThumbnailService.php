<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ThumbnailServiceInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\EncodedImage;
use Intervention\Image\Laravel\Facades\Image;

class ThumbnailService implements ThumbnailServiceInterface
{
    private Filesystem $thumbnailDisk;

    public function __construct()
    {
        $this->thumbnailDisk = Storage::disk('thumbnails');
    }

    /**
     * @throws \Exception
     */
    public function generate(string $fileName, string $filePath, ?int $width = null, ?int $height = null): string
    {
        if (!$width && !$height) {
            throw new \Exception('Width or height must be provided.');
        }

        $thumbnail = Image::read($filePath);
        $thumbnail = $thumbnail->scaleDown(
            $width ?? null,
            $height ?? null
        );

        $thumbnailPath = ($width ?? 'auto') . 'x' . ($height ?? 'auto') . '/' . $fileName;

        /** @var EncodedImage $thumbnail */
        if (!$this->thumbnailDisk->put($thumbnailPath, (string) $thumbnail->encode())) {
            throw new \Exception('Failed to save thumbnail.');
        }

        return $this->thumbnailDisk->path($thumbnailPath);
    }
}
