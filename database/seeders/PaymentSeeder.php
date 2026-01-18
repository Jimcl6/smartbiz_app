<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        // Get all completed appointments
        $appointments = Appointment::where('status', 'completed')->get();

        if ($appointments->isEmpty()) {
            return;
        }

        $methods = ['cash', 'gcash', 'bank', 'card'];

        // Create payments for completed appointments
        foreach ($appointments as $appointment) {
            // 80% chance of having a payment
            if (rand(1, 100) <= 80) {
                $status = $this->weightedRandom(['paid' => 85, 'pending' => 10, 'failed' => 5]);

                $payment = [
                    'appointment_id' => $appointment->id,
                    'amount' => $this->calculateAppointmentAmount($appointment),
                    'method' => $methods[array_rand($methods)],
                    'status' => $status,
                    'paid_at' => $status === 'paid' ? $this->generatePaymentDate($appointment->scheduled_at) : null,
                ];

                // Only create if payment doesn't already exist for this appointment
                if (!Payment::where('appointment_id', $appointment->id)->exists()) {
                    Payment::create($payment);
                }
            }
        }
    }

    private function weightedRandom($weights)
    {
        $rand = rand(1, 100);
        $total = 0;

        foreach ($weights as $value => $weight) {
            $total += $weight;
            if ($rand <= $total) {
                return $value;
            }
        }

        return array_key_first($weights);
    }

    private function calculateAppointmentAmount($appointment)
    {
        // Calculate total based on services
        $total = 0;
        foreach ($appointment->services as $service) {
            $total += $service->price;
        }

        // Add some variation
        $variation = rand(-5, 5);
        return max(15, $total + $variation);
    }

    private function generatePaymentDate($appointmentDate)
    {
        // Payment date is usually on the same day or within 2 days after appointment
        $daysAfter = rand(0, 2);
        return Carbon::parse($appointmentDate)->addDays($daysAfter);
    }
}
