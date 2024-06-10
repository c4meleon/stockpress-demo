<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ImageServiceInterface;
use App\Interfaces\ImageLocalStorageServiceInterface;
use App\Interfaces\ThumbnailServiceInterface;
use App\Models\Images;
use Intervention\Image\Laravel\Facades\Image;
use JetBrains\PhpStorm\ArrayShape;

class ImageService implements ImageServiceInterface
{
    public function __construct(
        protected ImageLocalStorageServiceInterface $imageStorageService,
        protected ThumbnailServiceInterface         $thumbnailService
    ) {
    }

    /**
     * @throws \Exception
     */
    public function save(string $fileName, string $filePath): string
    {
        $fileBody = file_get_contents($filePath);
        if (!$fileBody) {
            throw new \Exception('No file body provided.');
        }

        return $this->imageStorageService->save($fileName, $fileBody);
    }

    public function generateThumbnails(string $fileName, string $filePath, string $size): array
    {
        return $this->thumbnailService->generate($fileName, $filePath, $size);
    }

    #[ArrayShape(['width' => "int", 'height' => "int"])]
    public function getResolution(string $filePath): array
    {
        $image = Image::read($filePath);
        $width = $image->width();
        $height = $image->height();

        return ['width' => $width, 'height' => $height];
    }

    public function getExifData(string $filePath): array
    {
        $image = Image::read($filePath);
        $exifData = $image->exif();
        $exifDataArray = $exifData->toArray();
        return $this->sanitizeArray($exifDataArray);
    }

    public function deleteImage(int $id): void
    {
        try {
            $image = Images::findOrFail($id);
            $this->imageStorageService->delete($image->image_name);
            $image->delete();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    private function sanitizeArray($data): array
    {
        $sanitizedData = [];

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $sanitizedData[$key] = $this->sanitizeArray($value);
            } else {
                $sanitizedData[$key] = htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
            }
        }

        return $sanitizedData;
    }
}
