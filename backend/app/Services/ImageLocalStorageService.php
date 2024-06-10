<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ImageLocalStorageServiceInterface;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImageLocalStorageService implements ImageLocalStorageServiceInterface
{
    /**
     * @throws \Exception
     */
    public function save(string $filePath, string $fileBody): string
    {
        if (!Storage::disk('images')->put($filePath, $fileBody)) {
            throw new \Exception('Failed to store the image.');
        }

        return Storage::disk('images')->path($filePath);
    }

    public function get(string $filePath): ?string
    {
        return Storage::disk('images')->get($filePath);
    }

    public function download(string $filePath): StreamedResponse
    {
        return Storage::disk('images')->download($filePath);
    }

    public function delete(string $filePath): void
    {
        Storage::disk('images')->delete($filePath);
    }
}
