# Database Seeders Documentation

This document explains the realistic demo data seeders created for the SmartBiz application.

## Overview

The seeders populate the database with realistic demo data for:
- **Clients**: 15 realistic client profiles with contact information
- **Services**: 12 professional barber/salon services
- **Appointments**: 50 appointments spanning past 2 months to next 1 month
- **Payments**: Payment records linked to completed appointments

## Running the Seeders

### Fresh Installation
```bash
php artisan migrate:fresh --seed
```

### Existing Database
```bash
php artisan db:seed
```

### Multiple Runs Safe
All seeders are now **idempotent** - they can be run multiple times without creating duplicates:
- **ClientSeeder**: Checks for existing emails
- **ServiceSeeder**: Checks for existing service names  
- **AppointmentSeeder**: Checks for existing client+datetime combinations
- **PaymentSeeder**: Checks for existing appointment payments
- **DatabaseSeeder**: Checks for existing test user

This means you can safely run `php artisan db:seed` multiple times without errors.

## Data Details

### Clients (ClientSeeder)
- 15 realistic client profiles
- Includes names, emails, phone numbers
- Optional notes with customer preferences
- Examples: "Regular customer, prefers evening appointments", "Allergic to certain hair products"

### Services (ServiceSeeder)
12 professional services including:
- Classic Haircut ($25, 30min)
- Premium Haircut ($40, 45min) 
- Beard Trim & Shape ($15, 15min)
- Hot Towel Shave ($35, 30min)
- Hair Coloring ($85, 120min)
- Highlights ($120, 150min)
- Deep Conditioning Treatment ($35, 30min)
- Scalp Treatment ($25, 20min)
- Kids' Haircut ($20, 25min)
- Special Event Styling ($75, 90min)
- Hair & Beard Package ($35, 40min)
- Quick Trim ($10, 10min)

### Appointments (AppointmentSeeder)
- 50 appointments total
- Date range: Past 2 months to next 1 month
- Weighted status distribution:
  - Past appointments: 75% completed, 25% cancelled
  - Future appointments: 60% confirmed, 40% pending
- Each appointment has 1-3 services attached
- Realistic notes and scheduling patterns

### Payments (PaymentSeeder)
- Payments created only for completed appointments (80% coverage)
- Weighted status distribution:
  - 85% paid
  - 10% pending  
  - 5% failed
- Payment methods: cash, gcash, bank, card
- Amounts calculated based on service prices with slight variations
- Payment dates: same day or within 2 days of appointment

## Data Relationships

The seeders maintain proper relationships:
- Clients → Appointments (hasMany)
- Services ↔ Appointments (many-to-many via appointment_services)
- Appointments → Payments (hasMany)

## Realistic Features

### Time-based Logic
- Past appointments more likely to be completed
- Future appointments more likely to be confirmed
- Payment dates follow appointment dates logically

### Business Patterns
- Regular customers with weekly/monthly appointments
- Special occasion bookings
- Family appointments
- Service combinations (haircut + beard, etc.)

### Financial Realism
- Payment amounts reflect actual service pricing
- Multiple payment methods represented
- Some pending/failed payments for realism

## Factories Available

For custom data generation:
- `ClientFactory` - Generates realistic client data
- `ServiceFactory` - Generates professional services
- `AppointmentFactory` - Generates appointments with weighted statuses
- `PaymentFactory` - Generates payment records

## Usage Examples

### Generate specific number of records
```php
// In a custom seeder
Client::factory(25)->create();
Service::factory(15)->create();
Appointment::factory(100)->create();
Payment::factory(80)->create();
```

### Create specific scenarios
```php
// Create appointments for a specific client
$client = Client::find(1);
Appointment::factory(10)->create(['client_id' => $client->id]);
```

## Notes

- All seeders use `WithoutModelEvents` to prevent conflicts
- Data is designed to look realistic in the UI
- Appointments are distributed across different times and days
- Payment amounts are calculated based on actual service prices
