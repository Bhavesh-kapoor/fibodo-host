<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Check if file_name is a full URL (external image)
        if (filter_var($this->file_name, FILTER_VALIDATE_URL)) {
            $url = $this->file_name;
        } else {
            // Try to get the full URL for local files, if it fails, return default image
            try {
                $url = $this->getFullUrl();

                // Check if the URL is accessible (basic check)
                if (!$url || $url === '' || str_contains($url, 'null')) {
                    $url = $this->getDefaultImageUrl();
                } else {
                    // Check if the actual file exists on the disk
                    if (!$this->fileExists()) {
                        $url = $this->getDefaultImageUrl();
                    }
                }
            } catch (\Exception $e) {
                $url = $this->getDefaultImageUrl();
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->file_name,
            'url' => $url,
            'size' => $this->size,
            'mime_type' => $this->mime_type,
        ];
    }

    /**
     * Check if the media file actually exists on the disk
     *
     * @return bool
     */
    private function fileExists(): bool
    {
        try {
            $disk = Storage::disk($this->disk);
            return $disk->exists($this->getPathRelativeToRoot());
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the default image URL
     *
     * @return string
     */
    private function getDefaultImageUrl(): string
    {
        return asset('assets/media/default.svg');
    }
}
