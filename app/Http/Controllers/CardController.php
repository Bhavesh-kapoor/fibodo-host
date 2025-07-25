<?php

namespace App\Http\Controllers;

use App\Http\Requests\Card\CardRequest;
use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Services\CardService;
use Exception;
use Illuminate\Http\Request;

class CardController extends Controller
{
    public function __construct(private CardService $service) {}


    /**
     * getCards
     */
    public function index(CardRequest $request)
    {
        try {
            return response()->success('messages.success', $this->service->get($request));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * getCard
     */
    public function show(Card $card)
    {
        try {

            if (!\Auth::user()->canAccess($card->user_id)) {
                throw new Exception('messages.unauthorized', 403);
            }

            return response()->success('messages.success', new CardResource($card));
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * Todo: remove card from authorize.net.
     */
    // public function destroy(string $id)
    // {
    //     try {
    //         if (!\Auth::user()->canAccess($card->user_id)) {
    //             throw new Exception('messages.unauthorized', 403);
    //         }

    //         //return response()->success('messages.success', $this->service->destroy($card));
    //     } catch (Exception $e) {
    //         return response()->error($e->getMessage(), null, $e->getCode());
    //     }
    // }
}
