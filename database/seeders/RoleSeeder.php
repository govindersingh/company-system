<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Super Admin']);
        $admin = Role::create(['name' => 'Admin']);
        $clientManager = Role::create(['name' => 'Client Manager']);
        $developer = Role::create(['name' => 'Developer']);

        $admin->givePermissionTo([
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
            'delete-scrum'
        ]);

        $clientManager->givePermissionTo([
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
            'delete-scrum'
        ]);

        $developer->givePermissionTo([
            'show-project',
            
            'show-scrum',
            'create-scrum',
            'edit-scrum',
            'delete-scrum'
        ]);
    }
}
