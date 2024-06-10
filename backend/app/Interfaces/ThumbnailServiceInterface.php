<?php

declare(strict_types=1);

namespace App\Interfaces;

interface ThumbnailServiceInterface
{
    public function generate(string $fileName, string $filePath, string $size): array;
}
