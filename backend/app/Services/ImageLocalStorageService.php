<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ImageLocalStorageServiceInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageLocalStorageService implements ImageLocalStorageServiceInterface
{
    private Filesystem $imageDisk;
    private Filesystem $thumbnailDisk;

    public function __construct()
    {
        $this->imageDisk = Storage::disk('images');
        $this->thumbnailDisk = Storage::disk('thumbnails');
    }

    /**
     * @throws \Exception
     */
    public function save(string $filePath, string $fileBody): string
    {
        if (!$this->imageDisk->put($filePath, $fileBody)) {
            throw new \Exception('Failed to store the image.');
        }

        return $this->imageDisk->path($filePath);
    }

    public function get(string $filePath): ?string
    {
        return $this->imageDisk->get($filePath);
    }

    public function download(string $filePath): StreamedResponse
    {
        return $this->imageDisk->download($filePath);
    }

    public function delete(string $filePath): void
    {
        $this->imageDisk->delete($filePath);
    }

    public function deleteThumbnail(string $filePath): void
    {
        $this->thumbnailDisk->delete($filePath);
    }
}
