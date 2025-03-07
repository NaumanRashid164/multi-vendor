<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\RolesEnum;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678')
        ])->assignRole(RolesEnum::Admin->value);
        User::create([
            'name' => 'Vendor',
            'email' => 'vendor@vendor.com',
            'password' => Hash::make('12345678')
        ])->assignRole(RolesEnum::Vendor->value);
        User::create([
            'name' => 'User',
            'email' => 'user@user.com',
            'password' => Hash::make('12345678')
        ])->assignRole(RolesEnum::User->value);
    }
}
