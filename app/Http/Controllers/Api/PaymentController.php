<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\ActivityLog;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(): JsonResponse
    {
        $payments = Payment::with('appointment')->get();

        return response()->json([
            'payments' => $payments
        ]);
    }

    public function store(PaymentRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $payment = Payment::create([
            'appointment_id' => $validated['appointment_id'],
            'amount' => $validated['amount'],
            'method' => $validated['method'],
            'status' => $validated['status'] ?? 'pending',
            'paid_at' => $validated['paid_at'] ?? null,
        ]);

        $this->logActivity('recorded_payment', 'payment', $payment->id);

        return response()->json([
            'payment' => $payment->load('appointment'),
            'message' => 'Payment recorded successfully'
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $payment = Payment::with('appointment')->findOrFail($id);

        return response()->json([
            'payment' => $payment
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,paid,failed'],
            'paid_at' => ['nullable', 'date'],
        ]);

        if ($validated['status'] === 'paid' && !$validated['paid_at']) {
            $validated['paid_at'] = now()->toDateTimeString();
        }

        $payment->update($validated);

        $this->logActivity('updated_payment_status', 'payment', $payment->id);

        return response()->json([
            'payment' => $payment->load('appointment'),
            'message' => 'Payment status updated successfully'
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        $this->logActivity('deleted_payment', 'payment', $id);

        return response()->json([
            'message' => 'Payment deleted successfully'
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
