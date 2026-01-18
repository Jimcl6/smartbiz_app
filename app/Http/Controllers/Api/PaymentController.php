<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends BaseApiController
{
    public function index(): JsonResponse
    {
        $payments = Payment::with(['appointment.client:id,name'])
            ->orderBy('created_at', 'desc')
            ->get();

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

        return $this->successResponse('Payment recorded successfully', ['payment' => $payment->load('appointment')], 201);
    }

    public function show(string $id): JsonResponse
    {
        $payment = Payment::with(['appointment.client:id,name,email', 'appointment.services:id,name,price'])
            ->findOrFail($id);

        return response()->json([
            'payment' => $payment
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);

        $validatedData = $request->validate([
            'status' => ['required', 'in:pending,paid,failed'],
            'paid_at' => ['nullable', 'date'],
        ]);

        if ($validatedData['status'] === 'paid' && !$validatedData['paid_at']) {
            $validatedData['paid_at'] = now()->toDateTimeString();
        }

        $payment->update($validatedData);

        $this->logActivity('updated_payment_status', 'payment', $payment->id);

        return $this->successResponse('Payment status updated successfully', ['payment' => $payment->load('appointment')]);
    }

    public function destroy(string $id): JsonResponse
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        $this->logActivity('deleted_payment', 'payment', $id);

        return $this->successResponse('Payment deleted successfully');
    }

}
