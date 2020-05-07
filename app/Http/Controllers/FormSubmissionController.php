<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\Exports\FormSubmissionExport;
use App\FormSubmission;
use App\Http\Requests\ValidateFormSubmission;
use PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FormSubmissionController extends Controller
{
    private $modelName = 'Form_Submission';
    //
    public function index()
    {
        try {
            $formSubmissions = FormSubmission::all();
            $formSubmissions->load('user');
            $formSubmissions->load('formRequest');
            $formSubmissions->load('status');
            return ReturnGoodWay::successReturn(
                $formSubmissions,
                $this->modelName,
                "List of all " . $this->modelName,
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function store(ValidateFormSubmission $request)
    {
        try {
            $formSubmission = new FormSubmission();
            $formSubmission->form_request_id = $request->input('form_request_id');
            $formSubmission->date = $request->input('date');
            $formSubmission->used = $request->input('used');
            $formSubmission->balance = $request->input('balance');
            $formSubmission->allocation = $request->input('allocation');
            $formSubmission->notes = $request->input('notes');
            $formSubmission->save();
            return ReturnGoodWay::successReturn(
                $formSubmission,
                $this->modelName,
                $this->modelName . " has been stored",
                'created'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function show(FormSubmission $formSubmission, Request $request)
    {
        try {
            $formSubmission->load('user');
            $formSubmission->load('formRequest');
            $formSubmission->load('status');
            return ReturnGoodWay::successReturn(
                $formSubmission,
                $this->modelName,
                null,
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function update(FormSubmission $formSubmission, Request $request)
    {
        try {
            $formSubmission->update($request->all());
            return ReturnGoodWay::successReturn(
                $formSubmission,
                $this->modelName,
                $this->modelName . " with id " . $formSubmission->id . " has been updated",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function delete(FormSubmission $formSubmission)
    {
        try {
            $hidden = array(
                'user_id',
                'form_request_id',
                'date',
                'used',
                'balance',
                'allocation',
                'notes',
                'is_confirmed_pic',
                'is_confirmed_verificator',
                'is_confirmed_head_dept',
                'is_confirmed_head_office'
            );
            $formSubmission->delete();
            return ReturnGoodWay::successReturn(
                $formSubmission->makeHidden($hidden),
                $this->modelName,
                $this->modelName . " with id " . $formSubmission->id . " has been deleted",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    //Return count of submission form
    public function countSubmissionForm()
    {
        $count = FormSubmission::all()->count();
        return response()->json(['form_submission_count' => $count]);
    }

    public function exportExcel()
    {
        return Excel::download(new FormSubmissionExport, 'form_submission.xlsx');
    }

    // Print PDF
    public function exportPdf(Request $request)
    {
        switch ($request->frequency) {
            case 'yearly':
                $formSubmissions = FormSubmission::whereYear('date', $request->year)->orderBy('date', 'DESC')->get();
                break;
            case 'monthly':
                $formSubmissions = FormSubmission::whereYear('date', $request->year)->whereMonth('date', $request->month)->orderBy('date', 'DESC')->get();
                break;

            case 'daily':
                $formSubmissions = FormSubmission::whereDate('date', $request->date)->orderBy('date', 'DESC')->get();
                break;

            default:
                $formSubmissions = FormSubmission::orderBy('date', 'DESC')->get();
                break;
        }
        $formSubmissions->load('user');
        if ($request->date) {
            $request->date =  Carbon::parse($request->date)->translatedFormat('d F Y');
        }
        if ($request->frequency == 'monthly') {
            $request->month = Carbon::createFromDate($request->year, $request->month, 1)->translatedFormat('F');
        }

        $totalAmount = $formSubmissions->sum('amount');

        $pdf = PDF::loadview('pdf.form_submissions', [
            'formSubmissions' => $formSubmissions,
            'request' => $request,
            'totalAmount' => $totalAmount
        ])->setPaper('a4', 'landscape');
        return $pdf->download('Semua Form Submission.pdf');
    }

    public function exportSinglePdf(FormSubmission $formSubmission)
    {
        $formSubmission->with('user', 'budgetCode');
        $pdf = PDF::loadview('pdf.form_submission_single', ['formSubmission' => $formSubmission])->setPaper('a4', 'portrait');
        return $pdf->stream('Form Submission ' . $formSubmission->number . ".pdf");
    }
}
