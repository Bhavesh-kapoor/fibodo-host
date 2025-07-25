<?php

namespace App\Http\Controllers;

use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\User;
use App\Services\ClientService;
use Illuminate\Support\Facades\Response;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;
use App\Models\Client;
use App\Http\Requests\Client\ClientBookingRequest;
use App\Http\Resources\ClientBookingsResource;
use App\Http\Requests\Client\ClientInviteRequest;
use App\Http\Requests\Client\ClientRequest;

class ClientController extends Controller
{
    protected $service;
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        // initialize the service
        $this->service = new ClientService();
    }


    /**
     * getClients
     * 
     * @param ClientRequest $request
     * @return JsonResponse
     */
    public function index(ClientRequest $request): JsonResponse
    {
        try {
            return Response::success('messages.success', $this->service->get());
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * createClient
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function store(StoreClientRequest $request): JsonResponse
    {
        try {
            return Response::success('messages.success', $this->service->create($request), null, HttpResponse::HTTP_CREATED);
        } catch (Exception $e) {
            // Send error response
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * getClientDetails
     * @return JsonResponse
     */
    public function show(User $client): JsonResponse
    {
        try {
            // check if user can access the client
            if (!$client->hasBookingWithHost(\Auth::id()))
                throw new Exception("auth.unauthorized", 403);

            return Response::success('messages.success', new ClientResource($client));
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * updateClient
     *
     * @param UpdateClientRequest $request
     * @param User $client
     * @return JsonResponse
     */
    public function update(UpdateClientRequest $request, User $client): JsonResponse
    {
        try {
            return Response::success('messages.success', $this->service->update($client, $request->validated()));
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }


    /**
     * getBookings
     *
     * @param ClientBookingRequest $request
     * @param User $client
     * @return JsonResponse
     */
    public function bookings(ClientBookingRequest $request, User $client): JsonResponse
    {
        try {
            return Response::success(
                'messages.success',
                ClientBookingsResource::collection($this->service->getBookings($client))
            );
        } catch (\Exception $e) {
            throw $e;
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * inviteClient
     *
     * @param ClientInviteRequest $request
     * @return JsonResponse
     */
    public function invite(ClientInviteRequest $request): JsonResponse
    {
        try {
            return Response::success(
                'messages.success',
                $this->service->invite($request->validated()),
                null,
                HttpResponse::HTTP_CREATED
            );
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * archiveClient
     *
     * @param User $client
     * @return JsonResponse
     */
    public function archive(User $client): JsonResponse
    {
        try {
            return Response::success('messages.archived', $this->service->archive($client));
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * restoreClient
     *
     * @param User $client
     * @return JsonResponse
     */
    public function restore(User $client): JsonResponse
    {
        try {
            return Response::success('messages.restored', $this->service->restore($client));
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }
}
