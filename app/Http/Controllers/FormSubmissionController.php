<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\AdditionalHelper\UploadHelper;
use App\Exports\FormSubmissionExport;
use App\FormSubmission;
use App\Http\Requests\ValidateFormSubmission;
use App\Services\FormSubmissionService;
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
            $formSubmissions = FormSubmission::with(['formRequest', 'status'])->get();
            foreach ($formSubmissions as $formSubmission) {
                $formSubmission->pic = $formSubmission->pic()->first();
            }
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

    public function show(FormSubmission $formSubmission)
    {
        try {
            $formSubmission->load('formRequest', 'status', 'formRequest');
            $formSubmission->pic = $formSubmission->pic()->first();
            $formSubmission->head_dept = $formSubmission->head_dept()->first();
            $formSubmission->verificator = $formSubmission->verificator()->first();
            $formSubmission->head_office = $formSubmission->head_office()->first();
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
            $formSubmission->update($request->except('status_id'));
            if ($request->status_id) {
                $formSubmission->status_id = $request->status_id;
            }
            $formSubmission->save();
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

    public function exportExcel(Request $request)
    {
        return Excel::download(new FormSubmissionExport($request), 'formsubmission.xlsx');
    }

    // Print PDF
    public function exportPdf(Request $request)
    {
        switch ($request->frequency) {
            case 'yearly':
                $formSubmissions = FormSubmission::whereYear('date', $request->year)
                    ->orderBy('date', 'DESC')
                    ->get();
                break;
            case 'monthly':
                $formSubmissions = FormSubmission::whereYear('date', $request->year)->whereMonth('date', $request->month)
                    ->orderBy('date', 'DESC')
                    ->get();
                break;

            case 'daily':
                $formSubmissions = FormSubmission::whereDate('date', $request->date)
                    ->orderBy('date', 'DESC')
                    ->get();
                break;

            default:
                $formSubmissions = FormSubmission::orderBy('date', 'DESC')
                    ->orderByRaw("CASE WHEN number IS NOT NULL THEN 1 ELSE 2 END")
                    ->get();
                break;
        }
        if ($request->date) {
            $request->date =  Carbon::parse($request->date)->translatedFormat('d F Y');
        }
        if ($request->frequency == 'monthly') {
            $request->month = Carbon::createFromDate($request->year, $request->month, 1)->translatedFormat('F');
        }

        $sumRequestAmount = 0;

        foreach ($formSubmissions as $submission) {
            $sumRequestAmount += $submission->formRequest->amount;
        }

        $totalAmount = [
            'used' => $formSubmissions->sum('used'),
            'request' => $sumRequestAmount,
            'balance' => $formSubmissions->sum('balance')
        ];

        $pdf = PDF::loadview('pdf.form_submissions', [
            'formSubmissions' => $formSubmissions,
            'request' => $request,
            'totalAmount' => $totalAmount
        ])->setPaper('a4', 'landscape');
        return $pdf->stream('Semua Form Submission.pdf');
    }

    public function exportSinglePdf(FormSubmission $formSubmission)
    {
        $substr = env('APP_URL');
        $pathArray = [];
        foreach ($formSubmission->users as $user) {
            if ($user->pivot->attachment) {
                $path = explode($substr, $user->pivot->attachment)[1];
            } else {
                $path = NULL;
            }
            if ($path) {
                $pathPerRole = [
                    $user->pivot->role_name => $path
                ];
                $pathArray = array_merge($pathArray, $pathPerRole);
            }
        }
        $pdf = PDF::loadview('pdf.form_submission_single', [
            'formSubmission' => $formSubmission,
            'arrayOfPath' => $pathArray,
        ])->setPaper('a4', 'portrait');
        return $pdf->stream('Form Submission ' . $formSubmission->number . ".pdf");
    }

    public function confirm(FormSubmission $formSubmission, Request $request)
    {
        $user = auth()->user();
        $service = new FormSubmissionService($formSubmission);

        // Confirm as PIC
        if ($request->is_confirmed_pic) {
            $formSubmission->is_confirmed_pic = 1;
            if ($request->signature) {
                $uploadHelper = new UploadHelper(
                    $request->signature,
                    "signatures"
                );
                $filePath = $uploadHelper->insertAttachment();
                $userId = $formSubmission->users()->wherePivot('role_name', 'pic')->first()->id;
                $formSubmission->users()->updateExistingPivot($userId, ['attachment' => $filePath]);
            }
        }

        // Confirm as Verificator
        if ($request->is_confirmed_verificator) {
            $formSubmission->is_confirmed_verificator = 1;
            $service->createFormSubmissionUsers($request, 'verificator', $user);
        }

        // Confirm as Head Dept
        if ($request->is_confirmed_head_dept) {
            $formSubmission->is_confirmed_head_dept = 1;
            $service->createFormSubmissionUsers($request, 'head_dept', $user);
        }

        // Confirm as Head Office
        if ($request->is_confirmed_head_office) {
            $formSubmission->is_confirmed_head_office = 1;
            $service->createFormSubmissionUsers($request, 'head_office', $user);
        }

        // Confirm as Cashier
        if ($request->is_confirmed_cashier) {
            $formSubmission->is_confirmed_cashier = 1;
            $service->createFormSubmissionUsers($request, 'cashier', $user);
        }

        $formSubmission->save();
        $formSubmission->users;
        return ReturnGoodWay::successReturn(
            $formSubmission,
            $this->modelName,
            "Form Request has been successfully confirmed",
            'success'
        );
    }
}
