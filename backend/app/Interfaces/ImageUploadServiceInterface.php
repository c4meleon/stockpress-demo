<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Images;
use Illuminate\Http\Request;

interface ImageUploadServiceInterface
{
    public function handleUpload(Request $request): Images;
}
