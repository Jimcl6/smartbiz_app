<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\ClientRequest;
use App\Http\Requests\ClientUpdateRequest;
use App\Models\Client;
use Illuminate\Http\JsonResponse;

class ClientController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $clients = Client::withCount('appointments')
            ->orderBy('name')
            ->get();

        return response()->json([
            'clients' => $clients
        ]);
    }

    public function store(ClientRequest $request): JsonResponse
    {
        $client = Client::create($request->validated());

        $this->logActivity('created', 'client', $client->id);

        return $this->successResponse('Client created successfully', ['client' => $client], 201);
    }

    public function show(string $id): JsonResponse
    {
        $client = Client::withCount('appointments')
            ->findOrFail($id);

        return response()->json([
            'client' => $client
        ]);
    }

    public function update(ClientUpdateRequest $request, string $id): JsonResponse
    {
        $client = Client::findOrFail($id);
        $client->update($request->validated());

        $this->logActivity('updated', 'client', $client->id);

        return $this->successResponse('Client updated successfully', ['client' => $client]);
    }

    public function destroy(string $id): JsonResponse
    {
        $client = Client::findOrFail($id);
        $client->delete();

        $this->logActivity('deleted', 'client', $id);

        return $this->successResponse('Client deleted successfully');
    }

}
