<?php

namespace App\Services;

use Cloudinary\Cloudinary;

class CloudinaryService
{
    protected Cloudinary $cloudinary;

    public function __construct()
    {
        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key'    => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
            'url' => ['secure' => true],
        ]);
    }

    public function uploadFile($file, string $folder = 'products'): string
    {
        $result = $this->cloudinary->uploadApi()->upload(
            $file->getRealPath(),
            ['folder' => $folder, 'resource_type' => 'auto']
        );

        return $result['secure_url'] ?? '';
    }
}
