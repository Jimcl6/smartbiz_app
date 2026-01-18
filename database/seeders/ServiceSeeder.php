<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'Classic Haircut',
                'description' => 'Professional haircut with wash and styling',
                'duration_minutes' => 30,
                'price' => 25.00,
                'is_active' => true,
            ],
            [
                'name' => 'Premium Haircut',
                'description' => 'Deluxe haircut with hot towel treatment and styling',
                'duration_minutes' => 45,
                'price' => 40.00,
                'is_active' => true,
            ],
            [
                'name' => 'Beard Trim & Shape',
                'description' => 'Professional beard trimming and shaping',
                'duration_minutes' => 15,
                'price' => 15.00,
                'is_active' => true,
            ],
            [
                'name' => 'Hot Towel Shave',
                'description' => 'Traditional hot towel straight razor shave',
                'duration_minutes' => 30,
                'price' => 35.00,
                'is_active' => true,
            ],
            [
                'name' => 'Hair Coloring',
                'description' => 'Full head hair coloring with premium products',
                'duration_minutes' => 120,
                'price' => 85.00,
                'is_active' => true,
            ],
            [
                'name' => 'Highlights',
                'description' => 'Partial or full highlights',
                'duration_minutes' => 150,
                'price' => 120.00,
                'is_active' => true,
            ],
            [
                'name' => 'Deep Conditioning Treatment',
                'description' => 'Intensive moisture treatment for damaged hair',
                'duration_minutes' => 30,
                'price' => 35.00,
                'is_active' => true,
            ],
            [
                'name' => 'Scalp Treatment',
                'description' => 'Relaxing scalp massage and treatment',
                'duration_minutes' => 20,
                'price' => 25.00,
                'is_active' => true,
            ],
            [
                'name' => "Kids' Haircut (Under 12)",
                'description' => 'Gentle haircut for children',
                'duration_minutes' => 25,
                'price' => 20.00,
                'is_active' => true,
            ],
            [
                'name' => 'Special Event Styling',
                'description' => 'Professional styling for weddings, parties, etc.',
                'duration_minutes' => 90,
                'price' => 75.00,
                'is_active' => true,
            ],
            [
                'name' => 'Hair & Beard Package',
                'description' => 'Combined haircut and beard trim',
                'duration_minutes' => 40,
                'price' => 35.00,
                'is_active' => true,
            ],
            [
                'name' => 'Quick Trim',
                'description' => 'Bang trim or quick shape-up',
                'duration_minutes' => 10,
                'price' => 10.00,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            // Check if service already exists by name
            if (!Service::where('name', $service['name'])->exists()) {
                Service::create($service);
            }
        }
    }
}
