<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUs\ContactUsRequest;
use App\Http\Requests\ContactUs\ContactUsUpdateRequest;
use App\Http\Resources\ContactUsResource;
use App\Models\ContactUs;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(ContactUsRequest $request)
    {
        try {
            $contactUs = new ContactUs();
            $contactUs->host_id = auth()->id();
            $contactUs->subject = $request->subject;
            $contactUs->message = $request->message;
            $contactUs->save();

            return response()->success(
                'messages.created',
                new ContactUsResource($contactUs),
                null,
                201
            );
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $contactUs = ContactUs::all();
            return response()->success(
                'messages.success',
                ContactUsResource::collection($contactUs)
            );
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactUs $contactUs)
    {
        try {
            return response()->success(
                'messages.success',
                new ContactUsResource($contactUs)
            );
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ContactUsUpdateRequest $request, ContactUs $contactUs)
    {
        try {
            $contactUs->status = $request->status;
            $contactUs->save();

            return response()->success(
                'messages.updated',
                new ContactUsResource($contactUs)
            );
        } catch (Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }
} 