<?php

namespace Database\Factories;

use App\Models\Appointment;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        $statuses = ['pending', 'confirmed', 'completed', 'cancelled'];

        // Weighted distribution: 20% pending, 30% confirmed, 40% completed, 10% cancelled
        $statusWeights = [
            'pending' => 20,
            'confirmed' => 30,
            'completed' => 40,
            'cancelled' => 10,
        ];

        $status = $this->faker->randomElement(array_keys($statusWeights));

        return [
            'client_id' => Client::factory(),
            'scheduled_at' => $this->faker->dateTimeBetween('-2 months', '+2 months'),
            'status' => $status,
            'notes' => $this->faker->optional(0.4)->sentence(),
        ];
    }
}
