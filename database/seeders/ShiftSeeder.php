<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Seeder;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shift::updateOrCreate(
            ['name' => 'Day Shift'],
            [
                'description' => 'Standard day shift (8 AM - 5 PM)',
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'grace_period_minutes' => 15,
                'overtime_threshold_minutes' => 30,
                'is_active' => true,
            ]
        );

        Shift::updateOrCreate(
            ['name' => 'Morning Shift'],
            [
                'description' => 'Early morning shift (6 AM - 3 PM)',
                'start_time' => '06:00:00',
                'end_time' => '15:00:00',
                'grace_period_minutes' => 10,
                'overtime_threshold_minutes' => 30,
                'is_active' => true,
            ]
        );

        Shift::updateOrCreate(
            ['name' => 'Evening Shift'],
            [
                'description' => 'Evening shift (2 PM - 11 PM)',
                'start_time' => '14:00:00',
                'end_time' => '23:00:00',
                'grace_period_minutes' => 15,
                'overtime_threshold_minutes' => 30,
                'is_active' => true,
            ]
        );

        Shift::updateOrCreate(
            ['name' => 'Night Shift'],
            [
                'description' => 'Night shift (10 PM - 7 AM)',
                'start_time' => '22:00:00',
                'end_time' => '07:00:00',
                'grace_period_minutes' => 15,
                'overtime_threshold_minutes' => 30,
                'is_active' => true,
            ]
        );

        Shift::updateOrCreate(
            ['name' => 'Flexible Shift'],
            [
                'description' => 'Flexible hours (any 8-hour period)',
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'grace_period_minutes' => 30,
                'overtime_threshold_minutes' => 60,
                'is_active' => true,
            ]
        );
    }
}
