<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ImageLocalStorageServiceInterface;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageLocalStorageService implements ImageLocalStorageServiceInterface
{
    private Filesystem $disk;

    public function __construct()
    {
        $this->disk = Storage::disk('images');
    }

    /**
     * @throws \Exception
     */
    public function save(string $filePath, string $fileBody): string
    {
        if (!$this->disk->put($filePath, $fileBody)) {
            throw new \Exception('Failed to store the image.');
        }

        return $this->disk->path($filePath);
    }

    public function get(string $filePath): ?string
    {
        return $this->disk->get($filePath);
    }

    public function download(string $filePath): StreamedResponse
    {
        return $this->disk->download($filePath);
    }

    public function delete(string $filePath): void
    {
        $this->disk->delete($filePath);
    }
}
