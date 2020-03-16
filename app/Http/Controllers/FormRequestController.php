<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\AdditionalHelper\UploadHelper;
use App\Exceptions\FileNotSupportedException;
use App\FormRequest;
use App\Http\Requests\ValidateFormRequest;
use Exception;
use Illuminate\Http\Request;

class FormRequestController extends Controller
{
    private $modelName = 'Form_Request';

    // Get All
    public function index()
    {
        try {
            $formRequests = FormRequest::all();
            $formRequests->load('user');
            return ReturnGoodWay::successReturn(
                $formRequests,
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
    public function show($id)
    {
        try {
            $formRequest = FormRequest::findOrFail($id);
            $formRequest->user;
            return ReturnGoodWay::successReturn(
                $formRequest,
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
    public function store(ValidateFormRequest $request)
    {
        try {
            $form_request = new FormRequest();
            $form_request->user_id = auth()->user()->id;
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
                $this->modelName . " has been stored",
                'created'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Update existing model
    public function update($id, Request $request)
    {

        try {
            $formRequest = FormRequest::findOrFail($id);
            if ($request->user_id) $formRequest->user_id = $request->user_id;
            if ($request->date) $formRequest->date = $request->date;
            if ($request->method) $formRequest->method = $request->method;
            if ($request->allocation) $formRequest->allocation = $request->allocation;
            if ($request->amount) $formRequest->amount = $request->amount;
            if ($request->attachment) {
                $uploadHelper = new UploadHelper($this->modelName, $request->file('attachment'), uniqid(), 'proposal');
                $filePath = $uploadHelper->insertAttachment();
                $formRequest->attachment = $filePath;
            }
            if ($request->notes) $formRequest->notes = $request->notes;
            if ($request->is_confirmed_pic) $formRequest->is_confirmed_pic = $request->is_confirmed_pic;
            if ($request->is_confirmed_verificator) $formRequest->is_confirmed_verificator = $request->is_confirmed_verificator;
            if ($request->is_confirmed_head_dept) $formRequest->is_confirmed_head_dept = $request->is_confirmed_head_dept;
            if ($request->is_confirmed_cashier) $formRequest->is_confirmed_cashier = $request->is_confirmed_cashier;
            $formRequest->save();
            return ReturnGoodWay::successReturn(
                $formRequest,
                $this->modelName,
                $this->modelName . " with id " . $formRequest->id . " has been updated",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Delete one model
    public function destroy($id)
    {
        $hidden = array('is_confirmed_pic', 'is_confirmed_verificator', 'is_confirmed_head_dept', 'is_confirmed_cashier', 'user_id', 'method', 'attachment', 'notes');

        try {
            $formRequest = FormRequest::findOrFail($id);
            $formRequest->delete();
            return ReturnGoodWay::successReturn(
                $formRequest->makeHidden($hidden),
                $this->modelName,
                $this->modelName . " with id " . $formRequest->id . " has been deleted",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }
}
