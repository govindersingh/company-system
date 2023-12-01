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
            'email' => 'govinder@bytecode.com',
            'password' => Hash::make('govinder')
        ]);
        $superAdmin->assignRole('Super Admin');

        // Creating Admin User
        $admin = User::create([
            'name' => 'Gagandeep Singh', 
            'email' => 'gagan@bytecode.com',
            'password' => Hash::make('gagan')
        ]);
        $admin->assignRole('Admin');

        // Creating Client Manager User
        $clientManager = User::create([
            'name' => 'Gaurav thapa', 
            'email' => 'gaurav@bytecode.com',
            'password' => Hash::make('gaurav')
        ]);
        $clientManager->assignRole('Client Manager');

        // Creating Developer User
        $developerManager = User::create([
            'name' => 'Reshav', 
            'email' => 'reshav@bytecode.com',
            'password' => Hash::make('reshav')
        ]);
        $developerManager->assignRole('Developer');
    }
}
