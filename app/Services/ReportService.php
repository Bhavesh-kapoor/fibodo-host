<?php

namespace App\Services;

use App\Http\Resources\Reports\DashboardBookingsResource;
use App\Http\Resources\Reports\DashboardClientsResource;
use App\Models\Attendee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getDashboardClients()
    {
        try {
            // Get both new clients (last 7 days) and total clients in a single query
            $clientStats = Attendee::join('users', 'attendees.client_id', '=', 'users.id')
                ->where('attendees.host_id', Auth::id())
                ->select([
                    DB::raw('COUNT(DISTINCT users.id) as total_clients'),
                    DB::raw('COUNT(DISTINCT CASE WHEN DATE(users.created_at) >= ? THEN users.id END) as new_clients'),
                ])
                ->addBinding(now()->subDays(7)->format('Y-m-d'), 'select')
                ->first();

            // For the graph data, we need daily counts for the last 7 days
            $graphData = [];
            $startDate = now()->subDays(6)->startOfDay(); // 7 days including today

            // Generate empty data structure with all dates
            for ($i = 0; $i < 7; $i++) {
                $date = $startDate->copy()->addDays($i);
                $formattedDate = $date->format('Y-m-d');
                $graphData[$formattedDate] = [
                    'date' => $formattedDate,
                    'count' => 0
                ];
            }

            // Query to get daily counts of distinct new users
            $dailyCounts = Attendee::join('users', 'attendees.client_id', '=', 'users.id')
                ->where('attendees.host_id', Auth::id())
                ->where('users.created_at', '>=', $startDate)
                ->select([
                    DB::raw('DATE(users.created_at) as registration_date'),
                    DB::raw('COUNT(DISTINCT users.id) as count')
                ])
                ->groupBy('registration_date')
                ->get();

            // Map the actual counts to our data structure
            foreach ($dailyCounts as $dayData) {
                $dateKey = $dayData->registration_date;
                if (isset($graphData[$dateKey])) {
                    $graphData[$dateKey]['count'] = $dayData->count;
                }
            }

            // Convert to indexed array for easier frontend processing
            $graphDataArray = array_values($graphData);

            // Calculate percentage increase
            $total_clients = $clientStats->total_clients ?? 0;
            $new_clients = $clientStats->new_clients ?? 0;
            $percentage_increase = 0;

            if ($total_clients > 0) {
                $percentage_increase = ($new_clients / $total_clients) * 100;
            }

            // Previous period comparison (optional)
            $previous_clients = $total_clients - $new_clients;
            $growth_rate = 0;

            if ($previous_clients > 0) {
                $growth_rate = ($new_clients / $previous_clients) * 100;
            }

            // Prepare the response
            $response = [
                'total_clients' => $total_clients,
                'new_clients' => $new_clients,
                'percentage_of_total' => round($percentage_increase, 2),
                'growth_rate' => round($growth_rate, 2),
                'graph_data' => $graphDataArray
            ];

            return new DashboardClientsResource($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getDashboardBookings()
    {
        try {
            // Query for booking stats in the last 7 days
            $bookingStats = DB::table('bookings')
                ->where('host_id', Auth::id())
                ->where('created_at', '>=', now()->subDays(7)->format('Y-m-d'))
                ->select([
                    DB::raw('COUNT(*) as total_bookings'),
                    DB::raw('SUM(CASE WHEN is_walk_in = 1 THEN 1 ELSE 0 END) as walk_in_bookings'),
                    DB::raw('SUM(CASE WHEN is_walk_in = 0 THEN 1 ELSE 0 END) as online_bookings')
                ])
                ->first();

            // get attendees graph data 
            // For the graph data, we need daily counts for the last 7 days
            $graphData = [];
            $startDate = now()->subDays(6)->startOfDay(); // 7 days including today

            // Generate empty data structure with all dates
            for ($i = 0; $i < 7; $i++) {
                $date = $startDate->copy()->addDays($i);
                $formattedDate = $date->format('Y-m-d');
                $graphData[$formattedDate] = [
                    'date' => $formattedDate,
                    'count' => 0
                ];
            }

            // Query to get daily counts of attendees
            $dailyCounts = Attendee::join('bookings', 'attendees.booking_id', '=', 'bookings.id')
                ->where('bookings.host_id', Auth::id())
                ->where('bookings.created_at', '>=', $startDate)
                ->select([
                    DB::raw('DATE(bookings.created_at) as booking_date'),
                    DB::raw('COUNT(DISTINCT attendees.id) as count')
                ])
                ->groupBy('booking_date')
                ->get();

            // Map the actual counts to our data structure
            foreach ($dailyCounts as $dayData) {
                $dateKey = $dayData->booking_date;
                if (isset($graphData[$dateKey])) {
                    $graphData[$dateKey]['count'] = $dayData->count;
                }
            }

            // Convert to indexed array for easier frontend processing
            $graphDataArray = array_values($graphData);

            $response = [
                'total_bookings' => $bookingStats->total_bookings,
                'walk_in_bookings' => $bookingStats->walk_in_bookings,
                'online_bookings' => $bookingStats->online_bookings,
                'graph_data' => $graphDataArray
            ];

            return new DashboardBookingsResource($response);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
