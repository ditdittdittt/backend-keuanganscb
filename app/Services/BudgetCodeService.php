<?php

namespace App\Services;

use App\BudgetCode;
use App\BudgetCodeLog;

class BudgetCodeService
{
    protected $budgetCode;

    public function __construct(BudgetCode $budgetCode)
    {
        $this->budgetCode = $budgetCode;
    }

    public function createLog($number, $type, $nominal, $user_id)
    {
        $budgetCodeLog = new BudgetCodeLog();
        $budgetCodeLog->budget_code_id = $this->budgetCode->id;
        $budgetCodeLog->number = $number;
        $budgetCodeLog->type = $type;
        $budgetCodeLog->nominal = $nominal;
        $budgetCodeLog->user_id = $user_id;
        $budgetCodeLog->save();
    }
}
