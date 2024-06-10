<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ImageNameGeneratorInterface;

class ImageNameGenerator implements ImageNameGeneratorInterface
{
    public function generate(string $extension): string
    {
        return time() . '_' . uniqid() . '.' . $extension;
    }
}
