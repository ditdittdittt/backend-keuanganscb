<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\Exports\FormRequestExport;
use App\FormRequest;
use App\Http\Requests\FormRequestRequest;
use Carbon\Carbon;
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
    public function store(FormRequestRequest $request)
    {
        try {
            $formRequest = new FormRequest();
            $formRequest->date = $request->date;
            $formRequest->method = $request->method;
            $formRequest->allocation = $request->allocation;
            $formRequest->amount = $request->amount;
            $formRequest->budget_code_id = $request->budget_code_id;
            $formRequest->notes = $request->notes;
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
            $formRequest->update($request->except(['status_id', 'budget_code_id']));
            if ($request->status_id) {
                $formRequest->status_id = $request->status_id;
            }
            if ($request->budget_code_id) {
                $formRequest->budget_code_id = $request->budget_code_id;
            }
            if ($formRequest->method == "Transfer") {
                $formRequest->bank_name = $request->bank_name;
                $formRequest->bank_code = $request->bank_code;
                $formRequest->account_number = $request->account_number;
                $formRequest->account_owner = $request->account_owner;
            } else if ($formRequest->method == "Cash") {
                $formRequest->bank_name = null;
                $formRequest->bank_code = null;
                $formRequest->account_number = null;
                $formRequest->account_owner = null;
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
        return response()->json(['form_request_count' => $count]);
    }

    // Print PDF
    public function exportPdf(Request $request)
    {
        switch ($request->frequency) {
            case 'yearly':
                $formRequests = FormRequest::whereYear('date', $request->year)->orderBy('date', 'DESC')->get();
                break;
            case 'monthly':
                $formRequests = FormRequest::whereYear('date', $request->year)->whereMonth('date', $request->month)->orderBy('date', 'DESC')->get();
                break;

            case 'daily':
                $formRequests = FormRequest::whereDate('date', $request->date)->orderBy('date', 'DESC')->get();
                break;

            default:
                $formRequests = FormRequest::orderBy('date', 'DESC')->get();
                break;
        }
        $formRequests->load('user');
        if ($request->date) {
            $request->date =  Carbon::parse($request->date)->translatedFormat('d F Y');
        }
        if ($request->frequency == 'monthly') {
            $request->month = Carbon::createFromDate($request->year, $request->month, 1)->translatedFormat('F');
        }

        $totalAmount = $formRequests->sum('amount');

        $pdf = PDF::loadview('pdf.form_requests', [
            'formRequests' => $formRequests,
            'request' => $request,
            'totalAmount' => $totalAmount
        ])->setPaper('a4', 'landscape');
        return $pdf->download('Semua Form Request.pdf');
    }

    public function exportSinglePdf(FormRequest $formRequest)
    {
        $formRequest->with('user', 'budgetCode');
        $pdf = PDF::loadview('pdf.form_request_single', ['formRequest' => $formRequest])->setPaper('a4', 'portrait');
        return $pdf->download('Form Request ' . $formRequest->number . ".pdf");
    }

    public function exportExcel()
    {
        return Excel::download(new FormRequestExport, 'formrequest.xlsx');
    }
}
