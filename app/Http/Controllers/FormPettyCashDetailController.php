<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\FormPettyCash;
use App\FormPettyCashDetail;
use App\Http\Requests\ValidateFormPettyCashDetail;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FormPettyCashDetailController extends Controller
{
    private $modelName = 'Form_Petty_Cash_Detail';

    // Get All
    public function index($pettyCashId)
    {
        try {
            $formPettyCash = FormPettyCash::findOrFail($pettyCashId);
            return ReturnGoodWay::successReturn(
                $formPettyCash->detail,
                $this->modelName,
                "List of all form petty cash " . $pettyCashId . " detail",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Detail one model
    public function show($pettyCashId, $id)
    {
        try {
            $formPettyCash = FormPettyCash::findOrFail($pettyCashId);
            $formPettyCashDetail = $formPettyCash->detail->where('id', $id)->first();
            return ReturnGoodWay::successReturn(
                $formPettyCashDetail,
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
    public function store($pettyCashId, ValidateFormPettyCashDetail $request)
    {
        try {
            $formPettyCashDetail = new FormPettyCashDetail();
            $formPettyCashDetail->form_petty_cash_id = $pettyCashId;
            foreach ($request->input() as $key => $value) {
                $formPettyCashDetail->$key = $value;
            }
            // $formPettyCashDetail->budget_code = $request->input('budget_code');
            // $formPettyCashDetail->budget_name = $request->input('budget_name');
            // $formPettyCashDetail->nominal = $request->input('nominal');
            $formPettyCashDetail->save();
            return ReturnGoodWay::successReturn(
                $formPettyCashDetail,
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
    public function update($pettyCashId, $id, ValidateFormPettyCashDetail $request)
    {

        try {
            $form_petty_cash_detail = FormPettyCashDetail::findOrFail($id);
            foreach ($request->input() as $key => $value) {
                $form_petty_cash_detail->$key = $value;
            }
            // $form_petty_cash_detail->form_petty_cash_id = $request['form_petty_cash_id'];
            // $form_petty_cash_detail->budget_code = $request['budget_code'];
            // $form_petty_cash_detail->budget_name = $request['budget_name'];
            // $form_petty_cash_detail->nominal = $request['nominal'];
            $form_petty_cash_detail->save();
            return ReturnGoodWay::successReturn(
                $form_petty_cash_detail,
                $this->modelName,
                $this->modelName . " with id " . $form_petty_cash_detail->id . " has been updated",
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
        $hidden = array('created_at', 'form_petty_cash_id', 'updated_at', 'user_id');

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
