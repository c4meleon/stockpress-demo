<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Interfaces\ImageLocalStorageServiceInterface;
use App\Interfaces\ImageServiceInterface;
use App\Interfaces\ImageUploadServiceInterface;
use App\Models\Images;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;


class ImagesController extends Controller
{
    public function __construct(private readonly ImageUploadServiceInterface $imageUploadService,
                                private readonly ImageServiceInterface $imageService,
                                private readonly ImageLocalStorageServiceInterface $imageLocalService)
    {
    }

    public function index(): JsonResponse
    {
        $perPage = request()->input('page_size', 10);
        $cursor = request()->input('cursor');

        $photos = Images::orderBy('id', 'DESC')->cursorPaginate($perPage, ['*'], 'cursor', $cursor);

        return response()->json($photos);
    }

    public function upload(Request $request): JsonResponse
    {
        try {
            $image = $this->imageUploadService->handleUpload($request);
            return response()->json(['message' => 'File uploaded successfully.', 'image' => $image]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to upload file'], 500);
        }
    }

    public function downloadFile(string $image): StreamedResponse
    {
        return $this->imageLocalService->download($image);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->imageService->deleteImage($id);

            return response()->json([
                'message' => 'Images destroy'
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to delete image'], 500);
        }
    }
}
