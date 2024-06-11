<?php

declare(strict_types=1);

namespace App\Interfaces;

interface MetadataBuilderInterface
{
    public function setExifData(array $exifData): self;

    public function setFileSize(int $fileSize): self;

    public function setExtension(string $extension): self;

    public function setImageResolution(array $resolution): self;

    public function setTemperature(float $temperature): self;

    public function build(): array;
}
