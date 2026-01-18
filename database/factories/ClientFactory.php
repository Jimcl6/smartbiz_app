<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition(): array
    {
        $firstNames = ['John', 'Jane', 'Michael', 'Sarah', 'David', 'Emily', 'Robert', 'Lisa', 'James', 'Mary', 'William', 'Patricia', 'Richard', 'Jennifer', 'Charles', 'Linda'];
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez', 'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson'];

        $firstName = $this->faker->randomElement($firstNames);
        $lastName = $this->faker->randomElement($lastNames);
        $name = "$firstName $lastName";
        $email = strtolower($firstName . '.' . $lastName . '@' . $this->faker->freeEmailDomain());

        return [
            'name' => $name,
            'email' => $email,
            'phone' => $this->faker->phoneNumber(),
            'notes' => $this->faker->optional(0.3)->sentence(),
        ];
    }
}
