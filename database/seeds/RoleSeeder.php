<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            'name' => 'admin',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'head_office',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'manager_ops',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'pic',
            'guard_name' => 'web'
        ]);
        Role::create([
            'name' => 'verificator',
            'guard_name' => 'web'
        ]);
    }
}
