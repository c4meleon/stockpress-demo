<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ImageData;
use App\Http\Requests\ImageUploadRequest;
use App\Interfaces\ImageServiceInterface;
use App\Interfaces\ImageUploadServiceInterface;
use App\Interfaces\ImageUrlGeneratorServiceInterface;
use App\Models\Images;
use Illuminate\Http\Request;

class ImageUploadService implements ImageUploadServiceInterface
{
    public function __construct(private readonly ImageServiceInterface              $imageService,
                                private readonly ImageNameGenerator                 $imageNameGenerator,
                                private readonly ImageUrlGeneratorServiceInterface  $imageUrlGeneratorService,
                                private readonly WeatherService                     $weatherService)
    {
    }

    public function handleUpload(ImageUploadRequest $request): Images
    {
        $imageFile = $request->file('image');
        if (!$imageFile->isValid()) {
            throw new \Exception('No image file uploaded.');
        }

        $originalFileName = $imageFile->getClientOriginalName();
        $realPath = $imageFile->getRealPath();

        $uniqueFileName = $this->imageNameGenerator->generate($imageFile->getClientOriginalExtension());

        $exifData = $this->imageService->getExifData($realPath);

        $this->imageService->save($uniqueFileName, $realPath);
        $this->imageService->generateThumbnails($uniqueFileName, $realPath, '250x250');

        $imageUrl = $this->imageUrlGeneratorService->getUrl($uniqueFileName);
        $thumbnailsUrls = $this->imageUrlGeneratorService->getThumbnailsUrls($uniqueFileName, ['250x250']);

        $metaData = [
            'exif' => $exifData,
            'fileSize' => $imageFile->getSize(),
            'extension' => $imageFile->getClientOriginalExtension(),
            'imageResolution' => $this->imageService->getResolution($realPath),
            'temperature' => $this->weatherService->getTemperature(50.25841, 19.02754)
        ];

        $imageData = new ImageData(
            $request->get('name'),
            $request->get('email'),
            $originalFileName,
            $uniqueFileName,
            $imageUrl,
            $thumbnailsUrls,
            $metaData
        );

        return Images::create($imageData->toArray());
    }
}
