<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Models\ActivityLog;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index(): JsonResponse
    {
        $clients = Client::with('appointments')->get();

        return response()->json([
            'clients' => $clients
        ]);
    }

    public function store(ClientRequest $request): JsonResponse
    {
        $client = Client::create($request->validated());

        $this->logActivity('created', 'client', $client->id);

        return response()->json([
            'client' => $client,
            'message' => 'Client created successfully'
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $client = Client::with('appointments')->findOrFail($id);

        return response()->json([
            'client' => $client
        ]);
    }

    public function update(ClientUpdateRequest $request, string $id): JsonResponse
    {
        $client = Client::findOrFail($id);
        $client->update($request->validated());

        $this->logActivity('updated', 'client', $client->id);

        return response()->json([
            'client' => $client,
            'message' => 'Client updated successfully'
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $client = Client::findOrFail($id);
        $client->delete();

        $this->logActivity('deleted', 'client', $id);

        return response()->json([
            'message' => 'Client deleted successfully'
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
