<?php

declare(strict_types=1);

namespace App\DTO;

use Illuminate\Contracts\Support\Arrayable;
use JetBrains\PhpStorm\ArrayShape;

class ImageData implements Arrayable
{
    public function __construct(
        public string $name,
        public string $email,
        public string $imageName,
        public string $imageUniqueName,
        public string $imageUrl,
        public array $imageThumbnails,
        public array $imageMetadata,
    ) {
    }

    #[ArrayShape(['name' => "string", 'email' => "string", 'image_name' => "string", 'image_unique_name' => "string", 'image_url' => "string", 'image_thumbnails' => "array", 'image_metadata' => "array"])]
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'image_name' => $this->imageName,
            'image_unique_name' => $this->imageUniqueName,
            'image_url' => $this->imageUrl,
            'image_thumbnails' => $this->imageThumbnails,
            'image_metadata' => $this->imageMetadata,
        ];
    }
}
