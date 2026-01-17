<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use App\Models\ActivityLog;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    public function index(): JsonResponse
    {
        $appointments = Appointment::with(['client', 'services'])->get();

        return response()->json([
            'appointments' => $appointments
        ]);
    }

    public function store(AppointmentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $appointment = DB::transaction(function () use ($validated) {
            $appointment = Appointment::create([
                'client_id' => $validated['client_id'],
                'scheduled_at' => $validated['scheduled_at'],
                'status' => $validated['status'] ?? 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            $appointment->services()->attach($validated['service_ids']);

            return $appointment;
        });

        $this->logActivity('created', 'appointment', $appointment->id);

        return response()->json([
            'appointment' => $appointment->load('client', 'services'),
            'message' => 'Appointment created successfully'
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $appointment = Appointment::with(['client', 'services', 'payments'])->findOrFail($id);

        return response()->json([
            'appointment' => $appointment
        ]);
    }

    public function update(AppointmentUpdateRequest $request, string $id): JsonResponse
    {
        $appointment = Appointment::findOrFail($id);
        $validated = $request->validated();

        $appointment->update($validated);

        $this->logActivity('updated', 'appointment', $appointment->id);

        return response()->json([
            'appointment' => $appointment->load('client', 'services'),
            'message' => 'Appointment updated successfully'
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        $this->logActivity('deleted', 'appointment', $id);

        return response()->json([
            'message' => 'Appointment deleted successfully'
        ]);
    }

    public function attachService(string $appointment, string $service): JsonResponse
    {
        $appointment = Appointment::findOrFail($appointment);
        $service = Service::findOrFail($service);

        if ($appointment->services()->where('service_id', $service)->exists()) {
            return response()->json([
                'message' => 'Service is already attached to this appointment'
            ], 422);
        }

        $appointment->services()->attach($service);

        $this->logActivity('attached_service', 'appointment', $appointment->id);

        return response()->json([
            'message' => 'Service attached successfully'
        ]);
    }

    public function detachService(string $appointment, string $service): JsonResponse
    {
        $appointment = Appointment::findOrFail($appointment);
        $service = Service::findOrFail($service);

        if (!$appointment->services()->where('service_id', $service)->exists()) {
            return response()->json([
                'message' => 'Service is not attached to this appointment'
            ], 422);
        }

        $appointment->services()->detach($service);

        $this->logActivity('detached_service', 'appointment', $appointment->id);

        return response()->json([
            'message' => 'Service detached successfully'
        ]);
    }

    private function logActivity(string $action, string $entity, int $entityId): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'entity' => $entity,
            'entity_id' => $entityId,
        ]);
    }
}
