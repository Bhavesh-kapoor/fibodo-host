<?php

namespace App\Services;

use Auth;
use Exception;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Http\Resources\MediaResource;

class MediaService
{

    /**
     * getMedia
     *
     * @param  mixed $model
     * @return App\Http\Resources\MediaResource |  Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getMedia($model): \App\Http\Resources\MediaResource| \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        try {
            $media = $model->media()
                ->where('collection_name', $model->getTable() . "/" . request()->media_type)
                ->orderBy('created_at', 'desc')
                ->paginate();

            if (!$media->count()) throw new Exception('messages.not_found', 404);

            return ('gallery' == request()->media_type) ? MediaResource::collection($media) : new MediaResource($media->first());
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Upload media
     *
     * @param Request $request
     * @return string
     */
    public function upload(Request $request, $model): string|array
    {
        try {
            // upload and reuurn media url
            if ($request->media_type === 'gallery') {
                return collect($request->file('media'))->map(function ($file) use ($model, $request) {
                    return $model->addMedia($file)
                        ->toMediaCollection($model->getTable() . "/" . $request->media_type)
                        ->getUrl();
                })->toArray();
            }

            return $model
                ->addMediaFromRequest('media')
                ->toMediaCollection($model->getTable() . "/" . $request->media_type)
                ->getUrl();
        } catch (Exception $e) {
            throw $e;
        }
    }


    /**
     * Delete media
     *
     * @param Request $request
     * @return string
     */
    public function delete(Media $media)
    {
        try {
            // authorize 
            $model = $media->model_type::find($media->model_id);

            if (Auth::id() !== $model->user_id) {
                throw new Exception('You are not authorized to delete this media');
            }
            // delete media
            $media->delete();
        } catch (Exception $e) {
            throw $e;
        }
    }
}
