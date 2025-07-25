<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create a dummy company for owner
        $company = Company::create([
            'name' => 'Todaysky Company',
            'email' => 'todaysky@example.com',
            'address' => 'Tanzania',
            'contact_number' => '1234567890',
            'brela_reg_number' => '1234567890',
            'tin_number' => '1234567890',
            'isActive' => false
        ]);
        $company->delete();
        $admin = User::create([
            'name' => 'Todaysky Company',
            'company_id' => $company->id,
            'email' => 'todaysky@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole(Role::findByName('OWNER'));
    }
}
