<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\FormPettyCash;
use App\FormPettyCashDetail;
use App\Http\Requests\ValidateFormPettyCash;
use Exception;
use Illuminate\Http\Request;

class FormPettyCashController extends Controller
{
    private $modelName = 'Form_Petty_Cash';

    // Get All
    public function index()
    {
        try {
            $formPettyCashes = FormPettyCash::all();
            $formPettyCashes->load('user');
            return ReturnGoodWay::successReturn(
                $formPettyCashes,
                $this->modelName,
                "List of all form petty cash",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Detail one model
    public function show($id)
    {
        try {
            $formPettyCash = FormPettyCash::with('detail')->findOrFail($id);
            $formPettyCash->load('user');
            return ReturnGoodWay::successReturn(
                $formPettyCash,
                $this->modelName,
                null,
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Create new
    public function store(ValidateFormPettyCash $request)
    {
        try {
            $arrayOfDetails = $request->details;
            $formPettyCash = new FormPettyCash();
            $formPettyCash->user_id = auth()->user()->id;
            $formPettyCash->date = $request['date'];
            $formPettyCash->allocation = $request['allocation'];
            $formPettyCash->amount = $request['amount'];
            $formPettyCash->save();
            foreach ($arrayOfDetails as $detail) {
                $formPettyCashDetail = new FormPettyCashDetail();
                $formPettyCashDetail->form_petty_cash_id = $formPettyCash->id;
                $formPettyCashDetail->budget_code = $detail['budget_code_id'];
                $formPettyCashDetail->nominal = $detail['nominal'];
                $formPettyCashDetail->save();
            }
//            foreach ((array) $arrayOfDetails as $detail) {
//                $detailDecode = json_decode($detail);
//                foreach ((array) $detailDecode as $decoded){
//                    $formPettyCashDetail = new FormPettyCashDetail();
//                    $formPettyCashDetail->form_petty_cash_id = $formPettyCash->id;
//                    $formPettyCashDetail->budget_code = $decoded->budget_code;
//                    $formPettyCashDetail->budget_name = $decoded->budget_name;
//                    $formPettyCashDetail->nominal = $decoded->nominal;
//                    $formPettyCashDetail->save();
//                }
//            }
            return ReturnGoodWay::successReturn(
                $formPettyCash,
                $this->modelName,
                $this->modelName . " has been stored",
                'created'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Update existing model
    public function update($id, ValidateFormPettyCash $request)
    {

        try {
            $formPettyCash = FormPettyCash::findOrFail($id);
            // foreach ($request->input() as $key => $value) {
            //     $formPettyCash->$key = $value;
            // }
            if ($request->input('user_id')) {
                $formPettyCash->user_id = $request->input('user_id');
            }
            if ($request->input('date')) {
                $formPettyCash->date = $request->input('date');
            }
            if ($request->input('allocation')) {
                $formPettyCash->allocation = $request->input('allocation');
            }
            if ($request->input('amount')) {
                $formPettyCash->amount = $request->input('amount');
            }
            if ($request->input('is_confirmed_pic')) {
                $formPettyCash->is_confirmed_pic = $request->input('is_confirmed_pic');
            }
            if ($request->input('is_confirmed_manager_ops')) {
                $formPettyCash->is_confirmed_manager_ops = $request->input('is_confirmed_manager_ops');
            }
            if ($request->input('is_confirmed_cashier')) {
                $formPettyCash->is_confirmed_cashier = $request->input('is_confirmed_cashier');
            }
            if ($request->input('is_paid')) {
                $formPettyCash->is_paid = $request->input('is_paid');
            }
            $formPettyCash->save();
            return ReturnGoodWay::successReturn(
                $formPettyCash,
                $this->modelName,
                $this->modelName . " with id " . $formPettyCash->id . " has been updated",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Delete one model
    public function destroy($id, Request $request)
    {
        $hidden = array('is_confirmed_pic', 'is_confirmed_manager_ops', 'is_confirmed_cashier', 'user_id');

        try {
            $formPettyCash = FormPettyCash::findOrFail($id);
            $formPettyCash->delete();
            return ReturnGoodWay::successReturn(
                $formPettyCash->makeHidden($hidden),
                $this->modelName,
                $this->modelName . " with id " . $formPettyCash->id . " has been deleted",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Count Petty Cash
    public function countFormPettyCash(Request $request)
    {
        try {
            $condition = $request->condition;
            switch ($condition) {
                case 'daily':
                    $date = $request->date;
                    $totalFormPettyCashes = FormPettyCash::whereDate('created_at', $date)->count();
                    break;
                case 'monthly':
                    $month = $request->month;
                    $year = $request->year;
                    $date = $month . '-' . $year;
                    $totalFormPettyCashes = FormPettyCash::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
                    break;
                default:
                    $totalFormPettyCashes = FormPettyCash::all()->count();
                    return response()->json([
                        'total' => $totalFormPettyCashes
                    ], 200);
                    break;
            }
            return response()->json([
                'condition' => $condition,
                'date' => $date,
                'total' => $totalFormPettyCashes
            ], 200);
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }
}
