<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Http\Requests\ServiceUpdateRequest;
use App\Models\ActivityLog;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index(): JsonResponse
    {
        $services = Service::with('appointments')->get();

        return response()->json([
            'services' => $services
        ]);
    }

    public function store(ServiceRequest $request): JsonResponse
    {
        $service = Service::create($request->validated());

        $this->logActivity('created', 'service', $service->id);

        return response()->json([
            'service' => $service,
            'message' => 'Service created successfully'
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $service = Service::with('appointments')->findOrFail($id);

        return response()->json([
            'service' => $service
        ]);
    }

    public function update(ServiceUpdateRequest $request, string $id): JsonResponse
    {
        $service = Service::findOrFail($id);
        $service->update($request->validated());

        $this->logActivity('updated', 'service', $service->id);

        return response()->json([
            'service' => $service,
            'message' => 'Service updated successfully'
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $service = Service::findOrFail($id);
        $service->delete();

        $this->logActivity('deleted', 'service', $id);

        return response()->json([
            'message' => 'Service deleted successfully'
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
