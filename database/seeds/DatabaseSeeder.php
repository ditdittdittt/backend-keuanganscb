<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FormStatusSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(RekeningSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(BudgetCodeSeeder::class);
    }
}
