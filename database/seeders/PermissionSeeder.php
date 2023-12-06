<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create-role',
            'edit-role',
            'delete-role',

            'show-user',
            'create-user',
            'edit-user',
            'delete-user',

            'show-client',
            'create-client',
            'edit-client',
            'delete-client',

            'show-project',
            'create-project',
            'edit-project',
            'delete-project',

            'show-billing',
            'create-billing',
            'edit-billing',
            'delete-billing',
            
            'show-scrum',
            'create-scrum',
            'edit-scrum',
            'delete-scrum',

            'show-report',
            'create-report',
            'edit-report',
            'delete-report',
         ];
 
          // Looping and Inserting Array's Permissions into Permission Table
         foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
          }
    }
}
