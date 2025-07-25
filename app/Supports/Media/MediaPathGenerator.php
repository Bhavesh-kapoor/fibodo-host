<?php

namespace App\Supports\Media;


use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class MediaPathGenerator implements PathGenerator
{

    public function getPath(Media $media): string
    {
        return $this->getBasePath($media) . $media->collection_name . "/";
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getBasePath($media) . 'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getBasePath($media) . 'responsive/';
    }

    /*
     * Get a unique base path for the given media.
     */
    protected function getBasePath(Media $media): string
    {

        $prefix = config('media-library.prefix', '');

        if ($prefix !== '') {
            return $prefix . '/';
        }

        return '';
    }
}
