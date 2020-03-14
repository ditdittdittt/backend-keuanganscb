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
    public function show($id)
    {
        try {
            $form_request = FormPettyCash::findOrFail($id);
            return ReturnGoodWay::successReturn(
                $form_request,
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
    public function update($id, ValidateFormPettyCash $request)
    {

        try {
            $form_request = FormPettyCash::findOrFail($id);
            foreach ($request->input() as $key => $value) {
                $form_request->$key = $value;
            }
            // if ($request->input('user_id')) {
            //     $form_request->user_id = $request->input('user_id');
            // }
            // if ($request->input('date')) {
            //     $form_request->date = $request->input('date');
            // }
            // if ($request->input('allocation')) {
            //     $form_request->allocation = $request->input('allocation');
            // }
            // if ($request->input('amount')) {
            //     $form_request->amount = $request->input('amount');
            // }
            // if ($request->input('is_confirmed_pic')) {
            //     $form_request->is_confirmed_pic = $request->input('is_confirmed_pic');
            // }
            // if ($request->input('is_confirmed_manager_ops')) {
            //     $form_request->is_confirmed_manager_ops = $request->input('is_confirmed_manager_ops');
            // }
            // if ($request->input('is_confirmed_cashier')) {
            //     $form_request->is_confirmed_cashier = $request->input('is_confirmed_cashier');
            // }
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
    public function destroy($id, Request $request)
    {
        $hidden = array('is_confirmed_pic', 'is_confirmed_manager_ops', 'is_confirmed_cashier', 'user_id');

        try {
            $form_request = FormPettyCash::findOrFail($id);
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
