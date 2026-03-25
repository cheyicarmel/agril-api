<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageUploadService
{
    public function upload(UploadedFile $file, string $folder = 'agril'): string
    {
        $result = cloudinary()->upload($file->getRealPath(), [
            'folder' => $folder,
            'resource_type' => 'image',
            'transformation' => [
                'quality' => 'auto',
                'fetch_format' => 'auto',
            ],
        ]);

        return $result->getSecurePath();
    }

    public function delete(string $url): void
    {
        $publicId = $this->extractPublicId($url);
        if ($publicId) {
            cloudinary()->destroy($publicId);
        }
    }

    private function extractPublicId(string $url): ?string
    {
        preg_match('/upload\/(?:v\d+\/)?(.+)\.[a-z]+$/i', $url, $matches);
        return $matches[1] ?? null;
    }
}