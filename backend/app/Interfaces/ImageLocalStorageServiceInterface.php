<?php

declare(strict_types=1);

namespace App\Interfaces;

use Symfony\Component\HttpFoundation\StreamedResponse;

interface ImageLocalStorageServiceInterface
{
    public function save(string $filePath, string $fileBody): string;

    public function get(string $filePath): ?string;

    public function download(string $filePath): StreamedResponse;

    public function delete(string $filePath): void;

    public function deleteThumbnail(string $filePath): void;
}
