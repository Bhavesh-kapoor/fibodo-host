<?php

namespace App\Supports\Media;

use Spatie\MediaLibrary\Support\FileNamer\FileNamer;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\Conversions\Conversion;

class MediaFileNamer extends FileNamer
{
    public function originalFileName(string $fileName): string
    {
        // Generate a clean, unique filename
        $extLength = strlen(pathinfo($fileName, PATHINFO_EXTENSION));

        $baseName = substr($fileName, 0, strlen($fileName) - ($extLength ? $extLength + 1 : 0));
        $unique = Str::ulid();

        return "{$baseName}-{$unique}";
    }

    public function conversionFileName(string $fileName, Conversion $conversion): string
    {
        $strippedFileName = pathinfo($fileName, PATHINFO_FILENAME);

        return "{$strippedFileName}-{$conversion->getName()}";
    }

    public function responsiveFileName(string $fileName): string
    {
        return pathinfo($fileName, PATHINFO_FILENAME);
    }


    public function convertedFileName(Media $media): string
    {
        // Used for converted images like thumbnails, etc.
        return $this->originalFileName($media);
    }
}
