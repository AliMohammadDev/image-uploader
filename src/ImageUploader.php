<?php

namespace ImageUploader;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ImageUploader
{
    protected int $maxSize; // in KB
    protected array $allowedMimeTypes;
    protected string $disk;

    public function __construct(
        int $maxSize = 5120,
        array $allowedMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/pdf',
        ],
        string $disk = 'public'
    ) {
        $this->maxSize = $maxSize;
        $this->allowedMimeTypes = $allowedMimeTypes;
        $this->disk = $disk;
    }

    public function upload(UploadedFile $file, string $path = 'images'): string
    {
        $validator = Validator::make(['file' => $file], [
            'file' => 'required|file|max:' . $this->maxSize,
        ]);

        if ($validator->fails()) {
            throw new \InvalidArgumentException('File upload failed validation.');
        }

        if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            throw new \InvalidArgumentException('Invalid MIME type.');
        }

        $originalFileName = $file->getClientOriginalName();
        $sanitizedFileName = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $originalFileName);
        $baseFileName = strtolower(pathinfo($sanitizedFileName, PATHINFO_FILENAME));

        if (strpos($baseFileName, '.') !== false) {
            throw new \InvalidArgumentException('Invalid base filename.');
        }

        
        $sanitizedFileName = basename($sanitizedFileName);

        $uniqueId = Str::uuid();
        $fileName = $uniqueId . '_' . $sanitizedFileName;

        return Storage::disk($this->disk)->putFileAs($path, $file, $fileName);
    }

    public function update(UploadedFile $file, ?string $oldPath, string $path = 'images'): string
    {
        if ($oldPath && Storage::disk($this->disk)->exists($oldPath)) {
            Storage::disk($this->disk)->delete($oldPath);
        }
        return $this->upload($file, $path);
    }
}
