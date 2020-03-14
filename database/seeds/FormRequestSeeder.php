<?php

use Illuminate\Database\Seeder;

class FormRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\FormRequest::class, 20)->create();
    }
}
