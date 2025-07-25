<?php

namespace App\Services;

use App\Http\Requests\Card\CardRequest;
use App\Http\Resources\CardResource;
use App\Models\Card;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CardService
{
    public function get(CardRequest $request): AnonymousResourceCollection
    {
        try {
            $cards = \Auth::user()->cards()->paginate($request->per_page ?? config('app.per_page'));
            return CardResource::collection($cards);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
