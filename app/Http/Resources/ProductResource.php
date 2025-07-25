<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Schedule;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $price_settings =  new PriceResource($this);
        $attendee_settings = new AttendeeSettingResource($this);
        $location_settings = new LocationResource($this);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'sub_title' => $this->sub_title,
            'description' => $this->description,
            'session_duration' => $this->session_duration,
            'kcal_burn' => $this->kcal_burn,
            'category' => $this->whenLoaded('category', fn() => new CategoryResource($this->category)),
            'subCategory' => $this->whenLoaded('subCategory', fn() => new CategoryResource($this->subCategory)),
            'product_type' => $this->whenLoaded('productType', fn() => new ProductTypeResource($this->productType)),
            'activity_type' => $this->whenLoaded('activityType', fn() => new ActivityTypeResource($this->activityType)),
            'schedule' => $this->whenLoaded('schedule', fn() => new ScheduleResource($this->schedule)),

            // Group media by collection_name and transform each into MediaResource
            'media' => $this->when(
                $this->relationLoaded('media') && $this->media->isNotEmpty(),
                fn() => $this->media
                    ->whereIn('collection_name', ['products/portrait', 'products/landscape', 'products/gallery'])
                    ->groupBy('collection_name')
                    ->map(fn($items) => $items->count() > 1 ?  MediaResource::collection($items) : new MediaResource($items->first()))
            ),

            // attendee , price setting
            'attendee_settings' => $this->when(collect($attendee_settings)->filter()->isNotEmpty(), $attendee_settings),
            'price_settings' => $this->when(collect($price_settings)->filter()->isNotEmpty(), $price_settings),
            'location_settings' => $this->when(collect($location_settings)->filter()->isNotEmpty(), $location_settings),

            // acknowledgement Forms
            'forms' => $this->whenLoaded('forms', fn() => FormResource::collection($this->forms)),

            'status' => $this->status,
            'published_at' => $this->published_at,
            'archived_at' => $this->when(!is_null($this->archived_at), $this->archived_at),
            'created_at' => $this->created_at,
        ];
    }

    /**
     * selectable
     *
     * @return array
     */
    public function selectable(): array
    {
        return array_filter([
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status,
            'published_at' => $this->published_at,
            // schedules
            'schedules' => request()->has('schedules')
                ? $this->whenLoaded('schedule', fn() => WeeklyScheduleResource::collection($this->schedule->weeklySchedules)->map(fn($ws) => [
                    'id' => $ws->id,
                    'name' => $ws->name,
                    'is_default' => $ws->is_default,
                ]))
                : [],
        ]);
    }
}
