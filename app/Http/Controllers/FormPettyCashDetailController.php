<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\Exceptions\RelationDoesntMatchParentException;
use App\FormPettyCash;
use App\FormPettyCashDetail;
use App\Http\Requests\ValidateFormPettyCashDetail;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class FormPettyCashDetailController extends Controller
{
    private $modelName = 'Form_Petty_Cash_Detail';

    // Get All
    public function index(FormPettyCash $formPettyCash)
    {
        try {
            return ReturnGoodWay::successReturn(
                $formPettyCash->details,
                $this->modelName,
                "List of all form petty cash " . $formPettyCash->id . " details",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Detail one model
    public function show(FormPettyCash $formPettyCash, FormPettyCashDetail $formPettyCashDetail)
    {
        try {
            if ($formPettyCashDetail->form_petty_cash_id != $formPettyCash->id) {
                throw new ModelNotFoundException();
            };
            $formPettyCashDetail->load('budgetCode');
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
    public function store(FormPettyCash $formPettyCash, ValidateFormPettyCashDetail $request)
    {
        try {
            $formPettyCashDetail = new FormPettyCashDetail();
            $formPettyCashDetail->form_petty_cash_id = $formPettyCash->id;
            $formPettyCashDetail->budget_code = $request->input('budget_code');
            $formPettyCashDetail->budget_name = $request->input('budget_name');
            $formPettyCashDetail->nominal = $request->input('nominal');
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
    public function update(FormPettyCash $formPettyCash, FormPettyCashDetail $formPettyCashDetail, ValidateFormPettyCashDetail $request)
    {
        try {
            $formPettyCashDetail->budget_code = $request['budget_code'];
            $formPettyCashDetail->budget_name = $request['budget_name'];
            $formPettyCashDetail->nominal = $request['nominal'];
            $formPettyCashDetail->save();
            return ReturnGoodWay::successReturn(
                $formPettyCashDetail,
                $this->modelName,
                $this->modelName . " with id " . $formPettyCashDetail->id . " has been updated",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Delete one model
    public function destroy(FormPettyCash $formPettyCash, FormPettyCashDetail $formPettyCashDetail, Request $request)
    {
        $hidden = array('created_at', 'form_petty_cash_id', 'updated_at', 'user_id');
        try {
            $formPettyCashDetail->delete();
            return ReturnGoodWay::successReturn(
                $formPettyCashDetail->makeHidden($hidden),
                $this->modelName,
                $this->modelName . " with id " . $formPettyCashDetail->id . " has been deleted",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }
}
