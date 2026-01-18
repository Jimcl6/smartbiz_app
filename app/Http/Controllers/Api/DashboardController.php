<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Client;
use App\Models\Payment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function summary(): JsonResponse
    {
        $monthlyRevenue = $this->getMonthlyRevenue();
        $upcomingAppointments = $this->getUpcomingAppointments();
        $totalCounts = $this->getTotalCounts();

        return response()->json([
            'monthly_revenue' => $monthlyRevenue,
            'upcoming_appointments' => $upcomingAppointments,
            'total_counts' => $totalCounts
        ]);
    }

    public function monthlyRevenue(): JsonResponse
    {
        $revenue = $this->getMonthlyRevenue();

        return response()->json([
            'monthly_revenue' => $revenue
        ]);
    }

    public function upcomingAppointments(): JsonResponse
    {
        $appointments = $this->getUpcomingAppointments();

        return response()->json([
            'upcoming_appointments' => $appointments
        ]);
    }

    private function getMonthlyRevenue(): array
    {
        $currentMonth = now()->startOfMonth();
        $nextMonth = now()->addMonth()->startOfMonth();

        $revenue = Payment::where('status', 'paid')
            ->where('paid_at', '>=', $currentMonth)
            ->where('paid_at', '<', $nextMonth)
            ->selectRaw('DATE(paid_at) as date, SUM(amount) as daily_revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'revenue' => (float) $item->daily_revenue
                ];
            });

        $totalRevenue = $revenue->sum('revenue');

        return [
            'total' => $totalRevenue,
            'daily_breakdown' => $revenue->toArray(),
            'month' => now()->format('F Y')
        ];
    }

    private function getUpcomingAppointments(): array
    {
        return Appointment::with(['client', 'services'])
            ->where('scheduled_at', '>', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('scheduled_at', 'asc')
            ->limit(10)
            ->get()
            ->map(function ($appointment) {
                return [
                    'id' => $appointment->id,
                    'client_name' => $appointment->client->name,
                    'scheduled_at' => $appointment->scheduled_at,
                    'status' => $appointment->status,
                    'services' => $appointment->services->map(function ($service) {
                        return [
                            'name' => $service->name,
                            'duration_minutes' => $service->duration_minutes,
                            'price' => $service->price
                        ];
                    })
                ];
            })
            ->toArray();
    }

    private function getTotalCounts(): array
    {
        return [
            'clients' => Client::count(),
            'services' => Service::where('is_active', true)->count(),
            'appointments' => Appointment::count(),
            'appointments_today' => Appointment::whereDate('scheduled_at', today())->count(),
            'payments' => Payment::count(),
            'paid_payments' => Payment::where('status', 'paid')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
        ];
    }
}
