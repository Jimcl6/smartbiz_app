<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        // Get all clients and services
        $clients = Client::all();
        $services = Service::all();

        if ($clients->isEmpty() || $services->isEmpty()) {
            return;
        }

        $startDate = Carbon::now()->subMonths(2);
        $endDate = Carbon::now()->addMonths(1);

        // Create appointments for the past 2 months and next 1 month
        for ($i = 0; $i < 50; $i++) {
            $client = $clients->random();
            $appointmentDate = $this->generateRandomDate($startDate, $endDate);

            // Determine status based on date
            if ($appointmentDate->isPast()) {
                $status = $this->weightedRandom(['completed' => 75, 'cancelled' => 25]);
            } else {
                $status = $this->weightedRandom(['confirmed' => 60, 'pending' => 40]);
            }

            $appointment = [
                'client_id' => $client->id,
                'scheduled_at' => $appointmentDate,
                'status' => $status,
                'notes' => $this->randomOptionalSentence(),
                'created_at' => $appointmentDate->copy()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ];

            // Only create if appointment doesn't already exist
            if (
                !Appointment::where('client_id', $client->id)
                    ->where('scheduled_at', $appointmentDate)
                    ->exists()
            ) {
                $createdAppointment = Appointment::create($appointment);

                // Attach 1-3 services to each appointment
                $numServices = rand(1, 3);
                $selectedServices = $services->random($numServices);
                $serviceIds = $selectedServices->pluck('id')->toArray();
                $createdAppointment->services()->attach($serviceIds);
            }
        }
    }

    private function generateRandomDate($start, $end)
    {
        $timestamp = rand($start->timestamp, $end->timestamp);
        return Carbon::createFromTimestamp($timestamp);
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

    private function randomOptionalSentence()
    {
        $sentences = [
            null,
            null,
            'Customer prefers morning appointments',
            'First time customer',
            'Regular weekly appointment',
            'Special occasion preparation',
            'Sensitive scalp - use gentle products',
            'Customer running late',
            'Request for specific stylist',
        ];

        return $sentences[array_rand($sentences)];
    }
}
