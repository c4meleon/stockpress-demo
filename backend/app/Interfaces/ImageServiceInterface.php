<?php

declare(strict_types=1);

namespace App\Interfaces;

interface ImageServiceInterface
{
    public function save(string $fileName, string $filePath): string;

    public function generateThumbnails(string $fileName, string $filePath, ?int $width, ?int $height): string;

    public function getExifData(string $filePath): array;

    public function getResolution(string $filePath): array;
}
