<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $services = [
            [
                'name' => 'Haircut',
                'description' => 'Professional haircut and styling',
                'duration_minutes' => 30,
                'price' => 25.00,
            ],
            [
                'name' => 'Beard Trim',
                'description' => 'Beard shaping and trimming',
                'duration_minutes' => 15,
                'price' => 15.00,
            ],
            [
                'name' => 'Hair Coloring',
                'description' => 'Full hair coloring service',
                'duration_minutes' => 120,
                'price' => 80.00,
            ],
            [
                'name' => 'Hair Treatment',
                'description' => 'Deep conditioning treatment',
                'duration_minutes' => 45,
                'price' => 40.00,
            ],
            [
                'name' => 'Shave',
                'description' => 'Traditional straight razor shave',
                'duration_minutes' => 20,
                'price' => 20.00,
            ],
            [
                'name' => 'Kids Haircut',
                'description' => 'Haircut for children under 12',
                'duration_minutes' => 25,
                'price' => 20.00,
            ],
            [
                'name' => 'Styling',
                'description' => 'Hair styling for special occasions',
                'duration_minutes' => 60,
                'price' => 50.00,
            ],
            [
                'name' => 'Scalp Treatment',
                'description' => 'Relaxing scalp massage and treatment',
                'duration_minutes' => 30,
                'price' => 35.00,
            ],
        ];

        $service = $this->faker->randomElement($services);

        return [
            'name' => $service['name'],
            'description' => $service['description'],
            'duration_minutes' => $service['duration_minutes'],
            'price' => $service['price'],
            'is_active' => true,
        ];
    }
}
