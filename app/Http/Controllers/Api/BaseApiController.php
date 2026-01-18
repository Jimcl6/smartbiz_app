<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class BaseApiController extends Controller
{
    /**
     * Log user activity
     */
    protected function logActivity(string $action, string $entity, int $entityId): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'entity' => $entity,
            'entity_id' => $entityId,
        ]);
    }

    /**
     * Standard success response
     */
    protected function successResponse(string $message, $data = null, int $status = 200): \Illuminate\Http\JsonResponse
    {
        $response = ['message' => $message];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $status);
    }

    /**
     * Standard error response
     */
    protected function errorResponse(string $message, int $status = 400): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => $message], $status);
    }
}
