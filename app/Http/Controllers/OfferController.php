<?php

namespace App\Http\Controllers;

use App\Http\Requests\Offer\CreateOfferRequest;
use App\Http\Requests\Offer\UpdateOfferRequest;
use App\Http\Resources\OfferResource;
use App\Http\Resources\OfferTypeResource;
use App\Models\Offer;
use App\Models\OfferType;
use App\Services\OfferService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;

class OfferController extends Controller
{
    protected OfferService $offerService;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    /**
     * Get all offers with optional filtering and pagination
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            return Response::success('messages.success', $this->offerService->get($request->all()));
        } catch (Exception $e) {
            return Response::error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * Create a new offer
     *
     * @param CreateOfferRequest $request
     * @return JsonResponse
     */
    public function store(CreateOfferRequest $request): JsonResponse
    {
        try {
            $offer = $this->offerService->create($request->validated());
            return response()->json([
                'message' => 'Offer created successfully',
                'data' => $offer
            ], 201);
        } catch (Exception $e) {
            Log::error('Error creating offer: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating offer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific offer
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $offer = $this->offerService->find($id);
            return response()->json([
                'data' => $offer
            ]);
        } catch (Exception $e) {
            Log::error('Error fetching offer: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error fetching offer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing offer
     *
     * @param UpdateOfferRequest $request
     * @param Offer $offer
     * @return JsonResponse
     */
    public function update(UpdateOfferRequest $request, Offer $offer): JsonResponse
    {
        try {
            $updatedOffer = $this->offerService->update($offer, $request->validated());
            return response()->json([
                'message' => 'Offer updated successfully',
                'data' => $updatedOffer
            ]);
        } catch (Exception $e) {
            Log::error('Error updating offer: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error updating offer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an offer
     *
     * @param Offer $offer
     * @return JsonResponse
     */
    public function destroy(Offer $offer): JsonResponse
    {
        try {
            $this->offerService->delete($offer);
            return response()->json([
                'message' => 'Offer deleted successfully'
            ]);
        } catch (Exception $e) {
            Log::error('Error deleting offer: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error deleting offer',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download offer details as PDF
     *
     * @param Offer $offer
     * @return BinaryFileResponse|JsonResponse
     */
    public function download(Offer $offer): BinaryFileResponse|JsonResponse
    {
        try {
            $offer->load(['offerType', 'products', 'host']);

            $pdf = PDF::loadView('offers.download', [
                'offer' => $offer,
                'host' => $offer->host
            ]);

            $filename = 'offer_' . $offer->id . '_' . now()->format('Y-m-d') . '.pdf';

            return $pdf->stream($filename);
        } catch (\Exception $e) {
            Log::error('Error downloading offer: ' . $e->getMessage());
            return response()->json(['message' => 'Error generating PDF'], 500);
        }
    }
}
