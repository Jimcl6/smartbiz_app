<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'name' => 'John Smith',
                'email' => 'john.smith@email.com',
                'phone' => '+1-555-0101',
                'notes' => 'Regular customer, prefers evening appointments',
            ],
            [
                'name' => 'Sarah Johnson',
                'email' => 'sarah.j@email.com',
                'phone' => '+1-555-0102',
                'notes' => 'Allergic to certain hair products',
            ],
            [
                'name' => 'Michael Williams',
                'email' => 'm.williams@email.com',
                'phone' => '+1-555-0103',
                'notes' => null,
            ],
            [
                'name' => 'Emily Brown',
                'email' => 'emily.brown@email.com',
                'phone' => '+1-555-0104',
                'notes' => 'Prefers organic products',
            ],
            [
                'name' => 'David Garcia',
                'email' => 'd.garcia@email.com',
                'phone' => '+1-555-0105',
                'notes' => 'First-time customer',
            ],
            [
                'name' => 'Lisa Martinez',
                'email' => 'lisa.m@email.com',
                'phone' => '+1-555-0106',
                'notes' => 'Regular weekly appointments',
            ],
            [
                'name' => 'Robert Anderson',
                'email' => 'r.anderson@email.com',
                'phone' => '+1-555-0107',
                'notes' => 'Prefers morning appointments',
            ],
            [
                'name' => 'Jennifer Taylor',
                'email' => 'j.taylor@email.com',
                'phone' => '+1-555-0108',
                'notes' => 'Special occasion styling',
            ],
            [
                'name' => 'James Thomas',
                'email' => 'james.thomas@email.com',
                'phone' => '+1-555-0109',
                'notes' => null,
            ],
            [
                'name' => 'Mary Hernandez',
                'email' => 'mary.h@email.com',
                'phone' => '+1-555-0110',
                'notes' => 'Senior citizen discount applies',
            ],
            [
                'name' => 'William Moore',
                'email' => 'w.moore@email.com',
                'phone' => '+1-555-0111',
                'notes' => 'Beard trim regular',
            ],
            [
                'name' => 'Patricia Jackson',
                'email' => 'p.jackson@email.com',
                'phone' => '+1-555-0112',
                'notes' => 'Hair coloring every 6 weeks',
            ],
            [
                'name' => 'Richard Martin',
                'email' => 'richard.m@email.com',
                'phone' => '+1-555-0113',
                'notes' => null,
            ],
            [
                'name' => 'Linda Lee',
                'email' => 'linda.lee@email.com',
                'phone' => '+1-555-0114',
                'notes' => 'Family appointments - brings children',
            ],
            [
                'name' => 'Charles Perez',
                'email' => 'c.perez@email.com',
                'phone' => '+1-555-0115',
                'notes' => 'Corporate client',
            ],
        ];

        foreach ($clients as $client) {
            // Check if client already exists by email
            if (!Client::where('email', $client['email'])->exists()) {
                Client::create($client);
            }
        }
    }
}
