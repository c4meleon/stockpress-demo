<?php

declare(strict_types=1);

namespace App\Interfaces;

interface ImageNameGeneratorInterface
{
    public function generate(string $extension): string;
}
