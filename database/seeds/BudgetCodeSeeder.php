<?php

use App\BudgetCode;
use Illuminate\Database\Seeder;

class BudgetCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $budgetCode = new BudgetCode();
        $budgetCode->code = "A612.2";
        $budgetCode->balance = 900000000;
        $budgetCode->name = "Makan";
        $budgetCode->save();
    }
}
