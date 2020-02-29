<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\FormPettyCash;
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
            $form_requests = FormPettyCash::all();
            return ReturnGoodWay::successReturn(
                $form_requests,
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
    public function detail(Request $request)
    {
        try {
            $form_request = FormPettyCash::findOrFail($request['form_petty_cash_id']);
            return ReturnGoodWay::successReturn(
                $form_request,
                $this->modelName,
                $this->modelName . " with id " . $form_request->id,
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
            $form_request = new FormPettyCash();
            $form_request->user_id = $request['user_id'];
            $form_request->date = $request['date'];
            $form_request->allocation = $request['allocation'];
            $form_request->amount = $request['amount'];
            $form_request->save();
            return ReturnGoodWay::successReturn(
                $form_request,
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
    public function update(ValidateFormPettyCash $request)
    {

        try {
            $form_request = FormPettyCash::findOrFail($request['form_petty_cash_id']);
            $form_request->user_id = $request['user_id'];
            $form_request->date = $request['date'];
            $form_request->allocation = $request['allocation'];
            $form_request->amount = $request['amount'];
            if ($request['is_confirmed_pic']) {
                $form_request->is_confirmed_pic = $request['is_confirmed_pic'];
            }
            if ($request['is_confirmed_manager_ops']) {
                $form_request->is_confirmed_manager_ops = $request['is_confirmed_manager_ops'];
            }
            if ($request['is_confirmed_cashier']) {
                $form_request->is_confirmed_cashier = $request['is_confirmed_cashier'];
            }
            $form_request->save();
            return ReturnGoodWay::successReturn(
                $form_request,
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
        $hidden = array('created_at', 'is_confirmed_pic', 'is_confirmed_manager_ops', 'is_confirmed_cashier', 'updated_at', 'user_id');

        try {
            $form_request = FormPettyCash::findOrFail($request['form_petty_cash_id']);
            $form_request->delete();
            return ReturnGoodWay::successReturn(
                $form_request->makeHidden($hidden),
                $this->modelName,
                $this->modelName . " with id " . $form_request->id . " has been deleted",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }
}
