<?php

namespace Database\Seeders;

use App\Models\SettingOptions;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SettingOptions::create([
            'key' => 'user_check_attendance',
            'values' => json_encode(['yes', 'no'])
        ]);
    }
}
