<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::updateOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Administrator']
        );

        Role::updateOrCreate(
            ['name' => 'analyst'],
            ['display_name' => 'Analyst']
        );

        Role::updateOrCreate(
            ['name' => 'project_officer'],
            ['display_name' => 'Project Officer']
        );

        Role::updateOrCreate(
            ['name' => 'manager'],
            ['display_name' => 'Manager']
        );
    }
}
