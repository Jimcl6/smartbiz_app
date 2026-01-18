<?php

namespace Database\Factories;

use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        $methods = ['cash', 'gcash', 'bank', 'card'];
        $statuses = ['pending', 'paid', 'failed'];

        // Weighted distribution: 10% pending, 85% paid, 5% failed
        $statusWeights = [
            'pending' => 10,
            'paid' => 85,
            'failed' => 5,
        ];

        $status = $this->faker->randomElement(array_keys($statusWeights));

        return [
            'appointment_id' => Appointment::factory(),
            'amount' => $this->faker->randomFloat(2, 15, 200),
            'method' => $this->faker->randomElement($methods),
            'status' => $status,
            'paid_at' => $status === 'paid' ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
        ];
    }
}
