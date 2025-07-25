<?php

namespace App\Http\Controllers;

use App\Services\MediaService;
use Exception;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    protected $service;

    /**
     * deleteMedia
     *
     * @param  mixed $media
     * @return Illuminate\Http\JsonResponse
     */
    public function delete(Media $media, MediaService $mediaService): \Illuminate\Http\JsonResponse
    {
        try {
            $mediaService->delete($media);
            return response()->success('media.deleted');
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }
}
