<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Images;
use Illuminate\Http\UploadedFile;

interface ImageUploadServiceInterface
{
    public function handleUpload(UploadedFile $imageFile, string $name, string $email): Images;
}
