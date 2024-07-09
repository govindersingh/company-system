<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creating Super Admin User
        $superAdmin = User::create([
            'name' => 'Govinder Singh', 
            'email' => 'govinder.bytecode@gmail.com',
            'password' => Hash::make('govinder.bytecode@gmail.com')
        ]);
        $superAdmin->assignRole('Super Admin');

        // Creating Admin User
        $admin = User::create([
            'name' => 'Gagandeep Singh', 
            'email' => 'gagandeep.singh@bytecodetechnologies.in',
            'password' => Hash::make('gagandeep.singh@bytecodetechnologies.in')
        ]);
        $admin->assignRole('Admin');

        // Creating Client Manager User
        $clientManager = User::create([
            'name' => 'Gaurav thapa', 
            'email' => 'gaurav@bytecodetechnologies.in',
            'password' => Hash::make('gaurav@bytecodetechnologies.in')
        ]);
        $clientManager->assignRole('Client Manager');

        // Creating Developer User
        $developerManager = User::create([
            'name' => 'Reshav', 
            'email' => 'reshav@bytecodetechnologies.in',
            'password' => Hash::make('reshav@bytecodetechnologies.in')
        ]);
        $developerManager->assignRole('Developer');
    }
}
