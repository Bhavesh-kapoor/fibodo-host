<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct(private ReportService $service) {}

    public function getDashboardClients(Request $request)
    {
        try {
            return response()->success('messages.success', $this->service->getDashboardClients());
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }

    public function getDashboardBookings(Request $request)
    {
        try {
            return response()->success('messages.success', $this->service->getDashboardBookings());
        } catch (\Exception $e) {
            return response()->error($e->getMessage(), null, $e->getCode());
        }
    }
}
