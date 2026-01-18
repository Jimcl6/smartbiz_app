<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'appointment_id' => ['required', 'exists:appointments,id'],
            'amount' => ['required', 'numeric', 'min:0', 'regex:/^\d+(\.\d{1,2})?$/'],
            'method' => ['required', Rule::in(['cash', 'gcash', 'bank', 'card'])],
            'status' => ['nullable', Rule::in(['pending', 'paid', 'failed'])],
            'paid_at' => ['nullable', 'date'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->input('status') === 'paid' && !$this->input('paid_at')) {
            $this->merge([
                'paid_at' => now()->toDateTimeString()
            ]);
        }
    }
}
