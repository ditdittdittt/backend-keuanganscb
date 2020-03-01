<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\FormPettyCashDetail;
use App\Http\Requests\ValidateFormPettyCashDetail;
use Illuminate\Http\Request;

class FormPettyCashDetailController extends Controller
{
    private $modelName = 'Form_Petty_Cash_Detail';

    // Get All
    public function index()
    {
        try {
            $form_petty_cash_detail = FormPettyCashDetail::all();
            return ReturnGoodWay::successReturn(
                $form_petty_cash_detail,
                $this->modelName,
                "List of all form petty cash detail",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Detail one model
    public function detail(Request $request)
    {
        try {
            $form_petty_cash_detail = FormPettyCashDetail::findOrFail($request['form_petty_cash_detail_id']);
            return ReturnGoodWay::successReturn(
                $form_petty_cash_detail,
                $this->modelName,
                $this->modelName . " with id " . $form_petty_cash_detail->id,
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Create new
    public function store(ValidateFormPettyCashDetail $request)
    {
        try {
            $form_petty_cash_detail = new FormPettyCashDetail();
            $form_petty_cash_detail->form_petty_cash_id = $request['form_petty_cash_id'];
            $form_petty_cash_detail->budget_code = $request['budget_code'];
            $form_petty_cash_detail->budget_name = $request['budget_name'];
            $form_petty_cash_detail->nominal = $request['nominal'];
            $form_petty_cash_detail->save();
            return ReturnGoodWay::successReturn(
                $form_petty_cash_detail,
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
    public function update(ValidateFormPettyCashDetail $request)
    {

        try {
            $form_petty_cash_detail = FormPettyCashDetail::findOrFail($request['form_petty_cash_detail_id']);
            $form_petty_cash_detail->form_petty_cash_id = $request['form_petty_cash_id'];
            $form_petty_cash_detail->budget_code = $request['budget_code'];
            $form_petty_cash_detail->budget_name = $request['budget_name'];
            $form_petty_cash_detail->nominal = $request['nominal'];
            $form_petty_cash_detail->save();
            $form_petty_cash_detail->save();
            return ReturnGoodWay::successReturn(
                $form_petty_cash_detail,
                $this->modelName,
                $this->modelName . " with id " . $form_request->id . " has been updated",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Delete one model
    public function destroy(Request $request)
    {
        $hidden = array('created_at','form_petty_cash_id', 'updated_at', 'user_id');

        try {
            $form_petty_cash_detail = FormPettyCashDetail::findOrFail($request['form_petty_cash_detail_id']);
            $form_petty_cash_detail->delete();
            return ReturnGoodWay::successReturn(
                $form_petty_cash_detail->makeHidden($hidden),
                $this->modelName,
                $this->modelName . " with id " . $form_petty_cash_detail->id . " has been deleted",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }
}
