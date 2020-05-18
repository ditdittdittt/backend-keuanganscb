<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\BudgetCode;
use App\BudgetCodeLog;
use Illuminate\Http\Request;

class BudgetCodeLogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BudgetCode $budgetCode)
    {
        return ReturnGoodWay::successReturn(
            $budgetCode->logs,
            "Budget_Code_Log",
            "",
            'success'
        );
    }
}
