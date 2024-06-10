<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'image_name', 'image_unique_name', 'image_url', 'image_thumbnails', 'image_metadata'];

    protected $casts = [
        'image_metadata' => 'array',
        'image_thumbnails' => 'array',
    ];
}
