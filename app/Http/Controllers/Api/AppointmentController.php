<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AppointmentController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $appointments = Appointment::with(['client:id,name,email', 'services:id,name,price'])
            ->orderBy('scheduled_at', 'desc')
            ->get();

        return response()->json([
            'appointments' => $appointments
        ]);
    }

    public function store(AppointmentRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        $appointment = DB::transaction(function () use ($validatedData) {
            $appointment = Appointment::create([
                'client_id' => $validatedData['client_id'],
                'scheduled_at' => $validatedData['scheduled_at'],
                'status' => $validatedData['status'] ?? 'pending',
                'notes' => $validatedData['notes'] ?? null,
            ]);

            $appointment->services()->attach($validatedData['service_ids']);

            return $appointment;
        });

        $this->logActivity('created', 'appointment', $appointment->id);

        return $this->successResponse('Appointment created successfully', ['appointment' => $appointment->load('client', 'services')], 201);
    }

    public function show(string $id): JsonResponse
    {
        $appointment = Appointment::with([
            'client:id,name,email,phone',
            'services:id,name,description,price,duration_minutes',
            'payments:id,amount,method,status,paid_at'
        ])->findOrFail($id);

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

        return $this->successResponse('Appointment updated successfully', ['appointment' => $appointment->load('client', 'services')]);
    }

    public function destroy(string $id): JsonResponse
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        $this->logActivity('deleted', 'appointment', $id);

        return $this->successResponse('Appointment deleted successfully');
    }

    public function attachService(string $appointment, string $service): JsonResponse
    {
        $appointment = Appointment::findOrFail($appointment);
        $service = Service::findOrFail($service);

        if ($appointment->services()->where('service_id', $service)->exists()) {
            return $this->errorResponse('Service is already attached to this appointment', 422);
        }

        $appointment->services()->attach($service);

        $this->logActivity('attached_service', 'appointment', $appointment->id);

        return $this->successResponse('Service attached successfully');
    }

    public function detachService(string $appointment, string $service): JsonResponse
    {
        $appointment = Appointment::findOrFail($appointment);
        $service = Service::findOrFail($service);

        if (!$appointment->services()->where('service_id', $service)->exists()) {
            return $this->errorResponse('Service is not attached to this appointment', 422);
        }

        $appointment->services()->detach($service);

        $this->logActivity('detached_service', 'appointment', $appointment->id);

        return $this->successResponse('Service detached successfully');
    }

}
