<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'client_id' => ['required', 'exists:clients,id'],
            'scheduled_at' => [
                'required',
                'date',
                'after:now',
                function ($attribute, $value, $fail) {
                    $this->checkConflicts($value, $fail);
                }
            ],
            'status' => ['nullable', Rule::in(['pending', 'confirmed', 'completed', 'cancelled'])],
            'notes' => ['nullable', 'string'],
            'service_ids' => ['required', 'array', 'min:1'],
            'service_ids.*' => ['required', 'exists:services,id'],
        ];

        return $rules;
    }

    private function checkConflicts($scheduledAt, $fail): void
    {
        $clientId = $this->input('client_id');
        $appointmentId = $this->route('appointment');

        $conflicts = \App\Models\Appointment::where('client_id', $clientId)
            ->where('scheduled_at', $scheduledAt)
            ->where('status', '!=', 'cancelled')
            ->when($appointmentId, function ($query) use ($appointmentId) {
                return $query->where('id', '!=', $appointmentId);
            })
            ->exists();

        if ($conflicts) {
            $fail('The client already has an appointment scheduled at this time.');
        }
    }
}
