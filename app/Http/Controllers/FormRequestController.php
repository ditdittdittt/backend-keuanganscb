<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\AdditionalHelper\UploadHelper;
use App\Exceptions\RoleNotSupported;
use App\Exports\FormRequestExport;
use App\FormRequest;
use App\FormRequestUsers;
use App\Http\Requests\FormRequestRequest;
use App\Services\FormRequestService;
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
            $user = auth()->user();
            switch ($user) {

                    // Admin
                case $user->hasRole('admin'):
                    $formRequests = FormRequest::with(['status', 'details.budgetCode'])->get();
                    break;

                    // Head dept but also pic
                case $user->hasAllRoles(['head_dept', 'pic']):
                    $formRequests = FormRequest::with(['status'])
                        ->whereHas('users', function ($query) use ($user) {
                            $query->where('users.id', $user->id);
                        })
                        ->orWhere(function ($query) {
                            $query->where('is_confirmed_pic', 1);
                            $query->where('is_confirmed_head_dept', 0);
                        })->orderByRaw("CASE WHEN status_id = 4 THEN 1 ELSE 2 END")->get();
                    break;

                    // PIC
                case $user->hasRole('pic'):
                    $formRequests = FormRequest::with(['status'])
                        ->whereHas('users', function ($query) use ($user) {
                            $query->where('users.id', $user->id);
                        })->orderByRaw("CASE WHEN status_id = 4 THEN 1 ELSE 2 END")->get();
                    break;

                    // Head Dept
                case $user->hasRole('head_dept'):
                    $formRequests = FormRequest::with(['status'])
                        ->where(function ($query) {
                            $query->where('is_confirmed_pic', 1);
                            $query->where('is_confirmed_head_dept', 0);
                        })->get();
                    break;

                    // Verificator
                case $user->hasRole('verificator'):
                    $formRequests = FormRequest::with(['status'])
                        ->where(function ($query) {
                            $query->where('is_confirmed_pic', 1);
                            $query->where('is_confirmed_head_dept', 1);
                            $query->where('is_confirmed_verificator', 0);
                        })->get();
                    break;

                    // Head Office
                case $user->hasRole('head_office'):
                    $formRequests = FormRequest::with(['status'])
                        ->where(function ($query) {
                            $query->where('is_confirmed_pic', 1);
                            $query->where('is_confirmed_head_dept', 1);
                            $query->where('is_confirmed_verificator', 1);
                            $query->where('is_confirmed_head_office', 0);
                        })->get();
                    break;

                    // Cashier
                case $user->hasRole('cashier'):
                    $formRequests = FormRequest::with(['status'])
                        ->where(function ($query) {
                            $query->where('is_confirmed_pic', 1);
                            $query->where('is_confirmed_head_dept', 1);
                            $query->where('is_confirmed_verificator', 1);
                            $query->where('is_confirmed_head_office', 1);
                        })->orderByRaw("CASE WHEN status_id = 2 THEN 1 ELSE 2 END, created_at ASC")
                        ->get();
                    break;
            }

            // $formRequests->;


            foreach ($formRequests as $formRequest) {
                $formRequest->pic = $formRequest->pic()->first();
            }
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
            $formRequest->load('status', 'formSubmission', 'details', 'details.budgetCode');
            $pic = $formRequest->pic()->first();
            $verificator = $formRequest->verificator()->first();
            $head_dept = $formRequest->head_dept()->first();
            $head_office = $formRequest->head_office()->first();
            $formRequest->pic = $pic;
            $formRequest->verificator = $verificator;
            $formRequest->head_dept = $head_dept;
            $formRequest->head_office = $head_office;
            return ReturnGoodWay::successReturn(
                $formRequest,
                $this->modelName,
                "",
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
            $formRequest->method = $request->method;
            $formRequest->allocation = $request->allocation;
            $formRequest->amount = $request->amount;
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
            if ($request->bank_name) {
                $formRequest->bank_name = $request->bank_name;
            }
            if ($request->bank_code) {
                $formRequest->bank_code = $request->bank_code;
            }
            if ($request->account_number) {
                $formRequest->account_number = $request->account_number;
            }
            if ($request->account_owner) {
                $formRequest->account_owner = $request->account_owner;
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
        $formRequests->load('users');
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
        return $pdf->stream('Semua Form Request.pdf');
    }

    public function exportSinglePdf(FormRequest $formRequest)
    {
        $formRequest->with('users', 'budgetCode');
        $substr = env('APP_URL');
        $pathArray = [];
        foreach ($formRequest->users as $user) {
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
        $pdf = PDF::loadview('pdf.form_request_single', ['formRequest' => $formRequest, 'arrayOfPath' => $pathArray])->setPaper('a4', 'portrait');
        return $pdf->stream('Form Request ' . $formRequest->number . ".pdf");
    }

    public function exportExcel(Request $request)
    {
        return Excel::download(new FormRequestExport($request), 'formrequest.xlsx');
    }

    public function confirm(FormRequest $formRequest, Request $request)
    {
        $user = auth()->user();
        $service = new FormRequestService($formRequest);
        // Confirm as PIC
        if ($request->is_confirmed_pic) {
            $formRequest->is_confirmed_pic = 1;
            if ($request->signature) {
                $uploadHelper = new UploadHelper(
                    $request->signature,
                    "signatures"
                );
                $filePath = $uploadHelper->insertAttachment();
                $userId = $formRequest->users()->wherePivot('role_name', 'pic')->first()->id;
                $formRequest->users()->updateExistingPivot($userId, ['attachment' => $filePath]);
            }
        }

        // Confirm as Verificator
        if ($request->is_confirmed_verificator) {
            $formRequest->is_confirmed_verificator = 1;
            $service->createFormRequestUsers($request, 'verificator', $user);
        }

        // Confirm as Head Dept
        if ($request->is_confirmed_head_dept) {
            $formRequest->is_confirmed_head_dept = 1;
            $service->createFormRequestUsers($request, 'head_dept', $user);
        }

        // Confirm as Head Dept
        if ($request->is_confirmed_head_office) {
            $formRequest->is_confirmed_head_office = 1;
            $service->createFormRequestUsers($request, 'head_office', $user);
        }

        // Confirm as Cashier
        if ($request->is_confirmed_cashier) {
            $formRequest->is_confirmed_cashier = 1;
            $service->createFormRequestUsers($request, 'cashier', $user);
        }

        $formRequest->save();
        $formRequest->users;
        return ReturnGoodWay::successReturn(
            $formRequest,
            $this->modelName,
            "Form Request has been successfully confirmed",
            'success'
        );
    }
}
