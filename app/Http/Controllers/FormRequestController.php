<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\FormRequest;
use App\Http\Requests\ValidateFormRequest;
use Exception;
use Illuminate\Http\Request;

class FormRequestController extends Controller
{
    private $modelName = 'Form Request';

    // Get All
    public function index()
    {
        try {
            $form_requests = FormRequest::all();
            return ReturnGoodWay::successReturn(
                $form_requests,
                $this->modelName,
                "List of all form requests",
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
            $form_request = FormRequest::findOrFail($request->id);
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
    public function store(ValidateFormRequest $request)
    {
        try {
            $form_request = new FormRequest();
            $form_request->user_id = $request->user_id;
            $form_request->date = $request->date;
            $form_request->method = $request->method;
            $form_request->allocation = $request->allocation;
            $form_request->amount = $request->amount;
            $form_request->attachment = $request->attachment;
            $form_request->notes = $request->notes;
            $form_request->is_confirmed_pic = false;
            $form_request->is_confirmed_verificator = false;
            $form_request->is_confirmed_head_dept = false;
            $form_request->is_confirmed_cashier = false;
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
    public function update(ValidateFormRequest $request)
    {

        try {
            $form_request = FormRequest::findOrFail($request->form_request_id);
            $form_request->user_id = $request->user_id;
            $form_request->date = $request->date;
            $form_request->method = $request->method;
            $form_request->allocation = $request->allocation;
            $form_request->amount = $request->amount;
            $form_request->attachment = $request->attachment;
            $form_request->notes = $request->notes;
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
        $hidden = array('created_at', 'is_confirmed_pic', 'is_confirmed_verificator', 'is_confirmed_head_dept', 'is_confirmed_cashier', 'updated_at', 'user_id', 'method', 'attachment', 'notes');

        try {
            $form_request = FormRequest::findOrFail($request->form_request_id);
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
