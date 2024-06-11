<?php

namespace App\Providers;

use App\Builders\MetadataBuilder;
use App\Interfaces\ImageLocalStorageServiceInterface;
use App\Interfaces\ImageNameGeneratorInterface;
use App\Interfaces\ImageServiceInterface;
use App\Interfaces\ImageUploadServiceInterface;
use App\Interfaces\ImageUrlGeneratorServiceInterface;
use App\Interfaces\MetadataBuilderInterface;
use App\Interfaces\ThumbnailServiceInterface;
use App\Services\ImageLocalStorageService;
use App\Services\ImageNameGenerator;
use App\Services\ImageService;
use App\Services\ImageUploadService;
use App\Services\ImageUrlGeneratorService;
use App\Services\ThumbnailService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ImageUploadServiceInterface::class, ImageUploadService::class);
        $this->app->bind(ImageServiceInterface::class, ImageService::class);
        $this->app->bind(ImageNameGeneratorInterface::class, ImageNameGenerator::class);
        $this->app->bind(ImageLocalStorageServiceInterface::class, ImageLocalStorageService::class);
        $this->app->bind(ThumbnailServiceInterface::class, ThumbnailService::class);
        $this->app->bind(ImageUrlGeneratorServiceInterface::class, ImageUrlGeneratorService::class);
        $this->app->bind(MetadataBuilderInterface::class, MetadataBuilder::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
