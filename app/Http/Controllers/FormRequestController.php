<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\AdditionalHelper\UploadHelper;
use App\Exceptions\FileNotSupportedException;
use App\Exports\FormRequestExport;
use App\FormRequest;
use App\Http\Requests\ValidateFormRequest;
use PDF;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FormRequestController extends Controller
{
    private $modelName = 'Form_Request';

    // Get All
    public function index()
    {
        try {
            $formRequests = FormRequest::all();
            $formRequests->load('user', 'status', 'budgetCode');
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
    public function show(FormRequest $formRequest)
    {
        try {
            $formRequest->load('user', 'status', 'budgetCode');
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
            $formRequest = new FormRequest();
            $formRequest->user_id = auth()->user()->id;
            $formRequest->date = $request->date;
            $formRequest->method = $request->method;
            $formRequest->allocation = $request->allocation;
            $formRequest->amount = $request->amount;
            $formRequest->budget_code_id = $request->budget_code_id;
            if ($request->hasFile('attachment')) {
                $uploadHelper = new UploadHelper(
                    $this->modelName,
                    $request->file('attachment'),
                    uniqid(),
                    'proposal'
                );
                $filePath = $uploadHelper->insertAttachment();
                $formRequest->attachment = $filePath;
            } else {
                $formRequest->attachment = null;
            }
            $formRequest->notes = $request->notes;
            if ($request->bank_name) $formRequest->bank_name = $request->bank_name;
            if ($request->bank_code) $formRequest->bank_code = $request->bank_code;
            if ($request->account_number) $formRequest->account_number = $request->account_number;
            if ($request->account_owner) $formRequest->account_owner = $request->account_owner;
            $formRequest->save();
            return ReturnGoodWay::successReturn(
                $formRequest,
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
    public function update(FormRequest $formRequest, Request $request)
    {

        try {
            if ($request->user_id) $formRequest->user_id = $request->user_id;
            if ($request->date) $formRequest->date = $request->date;
            if ($request->method) $formRequest->method = $request->method;
            // Based on method
            if ($request->method == "Transfer") {
                $formRequest->bank_name = $request->bank_name;
                $formRequest->bank_code = $request->bank_code;
                $formRequest->account_number = $request->account_number;
                $formRequest->account_owner = $request->account_owner;
            } else {
                $formRequest->bank_name = null;
                $formRequest->bank_code = null;
                $formRequest->account_number = null;
                $formRequest->account_owner = null;
            }
            if ($request->allocation) $formRequest->allocation = $request->allocation;
            if ($request->amount) $formRequest->amount = $request->amount;
            if ($request->file('attachment') != null) {
                $uploadHelper = new UploadHelper($this->modelName, $request->file('attachment'), uniqid(), 'proposal');
                $filePath = $uploadHelper->insertAttachment();
                $formRequest->attachment = $filePath;
            } else {
                $formRequest->attachment = null;
            }
            if ($request->notes) $formRequest->notes = $request->notes;
            if (!is_null($request->is_confirmed_pic)) $formRequest->is_confirmed_pic = $request->is_confirmed_pic;
            if (!is_null($request->is_confirmed_verificator)) $formRequest->is_confirmed_verificator = $request->is_confirmed_verificator;
            if (!is_null($request->is_confirmed_head_dept)) $formRequest->is_confirmed_head_dept = $request->is_confirmed_head_dept;
            if (!is_null($request->is_confirmed_cashier)) $formRequest->is_confirmed_cashier = $request->is_confirmed_cashier;
            if ($request->status_id) $formRequest->status_id = $request->status_id;
            if ($request->budget_code_id) $formRequest->budget_code_id = $request->budget_code_id;

            $formRequest->save();
            if ($formRequest->is_confirmed_verificator && $formRequest->is_confirmed_head_dept && $formRequest->is_confirmed_pic && $formRequest->is_confirmed_cashier) {
                $formRequest->status_id = 2;
            } else {
                $formRequest->status_id = 1;
            }
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
    public function destroy(FormRequest $formRequest)
    {
        $hidden = array('is_confirmed_pic', 'is_confirmed_verificator', 'is_confirmed_head_dept', 'is_confirmed_cashier', 'user_id', 'method', 'attachment', 'notes');

        try {
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

    // Return count of request form
    public function countRequestForm()
    {
        $count = FormRequest::all()->count();
        return response()->json(['jumlah_request_form' => $count]);
    }

    // Print PDF
    public function printPdf()
    {
        $formRequests = FormRequest::orderBy('date', 'DESC')->get();
        $formRequests->load('user');
        // return response()->json($formRequests);
        $pdf = PDF::loadview('pdf.form_requests', ['formRequests' => $formRequests])->setPaper('a4', 'landscape');;
        return $pdf->stream();
    }

    public function exportExcel()
    {
        return Excel::download(new FormRequestExport, 'formrequest.xlsx');
    }
}
