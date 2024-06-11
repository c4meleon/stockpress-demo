<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Http\Requests\ImageUploadRequest;
use App\Models\Images;

interface ImageUploadServiceInterface
{
    public function handleUpload(ImageUploadRequest $request): Images;
}
