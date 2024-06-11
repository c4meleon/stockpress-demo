<?php

declare(strict_types=1);

namespace App\Services;

use App\Builders\MetadataBuilderInterface;
use App\Factories\ImageDataFactory;
use App\Interfaces\ImageServiceInterface;
use App\Interfaces\ImageUploadServiceInterface;
use App\Interfaces\ImageUrlGeneratorServiceInterface;
use App\Models\Images;
use Illuminate\Http\UploadedFile;

class ImageUploadService implements ImageUploadServiceInterface
{
    private const DEFAULT_THUMBNAIL_WIDTH = 250;
    private const DEFAULT_THUMBNAIL_SIZE = '250xauto';

    public function __construct(private readonly ImageServiceInterface              $imageService,
                                private readonly ImageNameGenerator                 $imageNameGenerator,
                                private readonly ImageUrlGeneratorServiceInterface  $imageUrlGeneratorService,
                                private readonly WeatherService                     $weatherService,
                                private readonly MetadataBuilderInterface           $metadataBuilder)
    {
    }

    /**
     * @throws \Exception
     */
    public function handleUpload(UploadedFile $imageFile, string $name, string $email): Images
    {
        $originalFileName = $imageFile->getClientOriginalName();
        $realPath = $imageFile->getRealPath();
        $uniqueFileName = $this->generateUniqueFileName($imageFile);

        $exifData = $this->imageService->getExifData($realPath);
        $this->processImage($uniqueFileName, $realPath);

        $imageUrl = $this->generateImageUrl($uniqueFileName);
        $thumbnailsUrls = $this->generateThumbnailsUrls($uniqueFileName);

        $metadata = $this->buildMetadata($imageFile, $exifData, $realPath);

        $imageData = ImageDataFactory::create(
            $name,
            $email,
            $originalFileName,
            $uniqueFileName,
            $imageUrl,
            $thumbnailsUrls,
            $metadata
        );

        return Images::create($imageData->toArray());
    }

    private function generateUniqueFileName(UploadedFile $imageFile): string
    {
        return $this->imageNameGenerator->generate($imageFile->getClientOriginalExtension());
    }

    private function processImage(string $uniqueFileName, string $realPath): void
    {
        $this->imageService->save($uniqueFileName, $realPath);
        $this->imageService->generateThumbnails($uniqueFileName, $realPath, self::DEFAULT_THUMBNAIL_WIDTH);
    }

    private function generateImageUrl(string $uniqueFileName): string
    {
        return $this->imageUrlGeneratorService->getUrl($uniqueFileName);
    }

    private function generateThumbnailsUrls(string $uniqueFileName): array
    {
        return $this->imageUrlGeneratorService->getThumbnailsUrls($uniqueFileName, [self::DEFAULT_THUMBNAIL_SIZE]);
    }

    private function buildMetadata(UploadedFile $imageFile, array $exifData, string $realPath): array
    {
        return $this->metadataBuilder
            ->setExifData($exifData)
            ->setFileSize($imageFile->getSize())
            ->setExtension($imageFile->getClientOriginalExtension())
            ->setImageResolution($this->imageService->getResolution($realPath))
            ->setTemperature($this->weatherService->getTemperature(50.25841, 19.02754))
            ->build();
    }
}
