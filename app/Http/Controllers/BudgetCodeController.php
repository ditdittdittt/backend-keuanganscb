<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\BudgetCode;
use Exception;
use Illuminate\Http\Request;
use App\AdditionalHelper\SeparateException;

class BudgetCodeController extends Controller
{
    protected $modelName = 'Budget_Code';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $budgetCodes = BudgetCode::all();
            return ReturnGoodWay::successReturn(
                $budgetCodes,
                $this->modelName,
                "List of all Budget Codes",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $budgetCode = new BudgetCode();
            $budgetCode->code = $request->code;
            $budgetCode->name = $request->name;
            $budgetCode->balance = $request->balance;
            $budgetCode->save();
            return ReturnGoodWay::successReturn(
                $budgetCode,
                $this->modelName,
                "Budget Code has been stored",
                'created'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BudgetCode  $budgetCode
     * @return \Illuminate\Http\Response
     */
    public function show(BudgetCode $budgetCode)
    {
        try {
            return ReturnGoodWay::successReturn(
                $budgetCode,
                $this->modelName,
                null,
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BudgetCode  $budgetCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BudgetCode $budgetCode)
    {
        try {
            if ($request->code) $budgetCode->code = $request->code;
            if ($request->name) $budgetCode->name = $request->name;
            if ($request->balance) $budgetCode->balance = $request->balance;
            $budgetCode->save();
            return ReturnGoodWay::successReturn(
                $budgetCode,
                $this->modelName,
                "Budget Code has been updated",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BudgetCode  $budgetCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(BudgetCode $budgetCode)
    {
        try {
            $budgetCode->delete();
            return ReturnGoodWay::successReturn(
                $budgetCode,
                $this->modelName,
                "Budget Code has been deleted",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    /**
     * Top up or decreasing Budget Code balance
     */
    public function topUpBalance(BudgetCode $budgetCode, Request $request)
    {
        $budgetCode->balance = $budgetCode->balance + $request->nominal;
        $budgetCode->save();
        return ReturnGoodWay::successReturn(
            $budgetCode,
            $this->modelName,
            "Budget balance update was successfully done",
            'success'
        );
    }
}
