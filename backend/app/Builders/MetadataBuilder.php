<?php

declare(strict_types=1);

namespace App\Builders;

class MetadataBuilder implements MetadataBuilderInterface
{
    private array $metadata = [];

    public function setExifData(array $exifData): self
    {
        $this->metadata['exif'] = $exifData;
        return $this;
    }

    public function setFileSize(int $fileSize): self
    {
        $this->metadata['fileSize'] = $fileSize;
        return $this;
    }

    public function setExtension(string $extension): self
    {
        $this->metadata['extension'] = $extension;
        return $this;
    }

    public function setImageResolution(array $resolution): self
    {
        $this->metadata['imageResolution'] = $resolution;
        return $this;
    }

    public function setTemperature(float $temperature): self
    {
        $this->metadata['temperature'] = $temperature;
        return $this;
    }

    public function build(): array
    {
        return $this->metadata;
    }
}
