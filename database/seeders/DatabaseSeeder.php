<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@kuafor.com',
            'password' => Hash::make('password'),
            'role' => 'super',
        ]);

        $services = [
            ['name' => 'Boyama', 'description' => 'Saç boyama', 'duration' => 90, 'price' => 350, 'approval_type' => 'manual'],
            ['name' => 'Damat Tıraşı', 'description' => 'Damat tıraşı paketi', 'duration' => 60, 'price' => 2000, 'approval_type' => 'manual'],
            ['name' => 'Sakal Tıraşı', 'description' => 'Ustura ile sakal tıraşı', 'duration' => 20, 'price' => 75, 'approval_type' => 'auto'],
            ['name' => 'Saç + Sakal', 'description' => 'Saç kesimi ve sakal tıraşı', 'duration' => 45, 'price' => 200, 'approval_type' => 'auto'],
            ['name' => 'Saç Kesimi', 'description' => 'Klasik saç kesimi', 'duration' => 30, 'price' => 150, 'approval_type' => 'auto'],
            ['name' => 'Çocuk Saç Kesimi', 'description' => '12 yaş altı', 'duration' => 25, 'price' => 80, 'approval_type' => 'auto'],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Default settings
        Setting::set('salon_name', 'AutoDetail Pro');
        Setting::set('phone', '');
        Setting::set('address', '');
        Setting::set('working_hours_start', '08:00');
        Setting::set('working_hours_end', '19:00');
        Setting::set('working_days', json_encode([1,2,3,4,5,6]));
        Setting::set('slot_interval', '60');
        Setting::set('theme_color', '#0d2137');
        Setting::set('accent_color', '#06b6d4');

        $this->call(CarBrandSeeder::class);
    }
}