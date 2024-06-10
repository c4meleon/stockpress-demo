<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Images;
use Illuminate\Http\Request;

interface ImageUrlGeneratorServiceInterface
{
    public function getUrl(string $fileName): string;

    public function getThumbnailsUrls(string $filename, array $sizes): array;
}
