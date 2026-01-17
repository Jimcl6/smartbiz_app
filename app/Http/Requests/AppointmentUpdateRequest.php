<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $appointmentId = $this->route('appointment');

        $rules = [
            'client_id' => ['required', 'exists:clients,id'],
            'scheduled_at' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($appointmentId) {
                    $this->checkConflicts($value, $fail, $appointmentId);
                }
            ],
            'status' => ['required', Rule::in(['pending', 'confirmed', 'completed', 'cancelled'])],
            'notes' => ['nullable', 'string'],
        ];

        return $rules;
    }

    private function checkConflicts($scheduledAt, $fail, $appointmentId): void
    {
        $clientId = $this->input('client_id');

        $conflicts = \App\Models\Appointment::where('client_id', $clientId)
            ->where('scheduled_at', $scheduledAt)
            ->where('status', '!=', 'cancelled')
            ->where('id', '!=', $appointmentId)
            ->exists();

        if ($conflicts) {
            $fail('The client already has an appointment scheduled at this time.');
        }
    }
}
