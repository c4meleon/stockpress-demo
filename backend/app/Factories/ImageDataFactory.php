<?php

declare(strict_types=1);

namespace App\Factories;

use App\DTO\ImageData;

class ImageDataFactory
{
    public static function create(
        string $name,
        string $email,
        string $originalFileName,
        string $uniqueFileName,
        string $imageUrl,
        array $thumbnailsUrls,
        array $metadata
    ): ImageData {
        return new ImageData(
            $name,
            $email,
            $originalFileName,
            $uniqueFileName,
            $imageUrl,
            $thumbnailsUrls,
            $metadata
        );
    }
}
