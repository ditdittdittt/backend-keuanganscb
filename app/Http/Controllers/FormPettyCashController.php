<?php

namespace App\Http\Controllers;

use App\AdditionalHelper\ReturnGoodWay;
use App\AdditionalHelper\SeparateException;
use App\Exports\FormPettyCashExport;
use App\FormPettyCash;
use App\Http\Requests\FormPettyCashRequest;
use Carbon\Carbon;
use PDF;
use Exception;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FormPettyCashController extends Controller
{
    private $modelName = 'Form_Petty_Cash';

    // Get All
    public function index()
    {
        try {
            $formPettyCashes = FormPettyCash::with(['status'])->get();
            foreach ($formPettyCashes as $formPettyCash) {
                $formPettyCash->pic = $formPettyCash->pic()->first();
            }
            return ReturnGoodWay::successReturn(
                $formPettyCashes,
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
    public function show(FormPettyCash $formPettyCash)
    {
        try {
            $formPettyCash->load(['details', 'details.budgetCode', 'status']);
            $formPettyCash->pic = $formPettyCash->pic()->first();
            $formPettyCash->manager_ops = $formPettyCash->manager_ops()->first();
            $formPettyCash->cashier = $formPettyCash->cashier()->first();
            return ReturnGoodWay::successReturn(
                $formPettyCash,
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
    public function store(FormPettyCashRequest $request)
    {
        try {
            $formPettyCash = new FormPettyCash();
            $formPettyCash->date = $request['date'];
            $formPettyCash->allocation = $request['allocation'];
            $formPettyCash->amount = $request['amount'];
            $formPettyCash->save();
            return ReturnGoodWay::successReturn(
                $formPettyCash,
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
    public function update(FormPettyCash $formPettyCash, FormPettyCashRequest $request)
    {
        try {
            $formPettyCash->update($request->all());
            return ReturnGoodWay::successReturn(
                $formPettyCash,
                $this->modelName,
                $this->modelName . " with id " . $formPettyCash->id . " has been updated",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Delete one model
    public function destroy(FormPettyCash $formPettyCash, Request $request)
    {
        $hidden = array('is_confirmed_pic', 'is_confirmed_manager_ops', 'is_confirmed_cashier', 'user_id');

        try {
            $formPettyCash->delete();
            return ReturnGoodWay::successReturn(
                $formPettyCash->makeHidden($hidden),
                $this->modelName,
                $this->modelName . " with id " . $formPettyCash->id . " has been deleted",
                'success'
            );
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    // Count Petty Cash
    public function countFormPettyCash(Request $request)
    {
        try {
            $condition = $request->condition;
            switch ($condition) {
                case 'daily':
                    $date = $request->date;
                    $totalFormPettyCashes = FormPettyCash::whereDate('created_at', $date)->count();
                    break;
                case 'monthly':
                    $month = $request->month;
                    $year = $request->year;
                    $date = $month . '-' . $year;
                    $totalFormPettyCashes = FormPettyCash::whereYear('created_at', $year)->whereMonth('created_at', $month)->count();
                    break;
                default:
                    $totalFormPettyCashes = FormPettyCash::all()->count();
                    return response()->json([
                        'form_petty_cash_count' => $totalFormPettyCashes
                    ], 200);
                    break;
            }
            return response()->json([
                'condition' => $condition,
                'date' => $date,
                'form_petty_cash_count' => $totalFormPettyCashes
            ], 200);
        } catch (Exception $err) {
            $error = new SeparateException($err);
            return $error->checkException($this->modelName);
        }
    }

    public function exportExcel()
    {
        return Excel::download(new FormPettyCashExport, 'formpettycash.xlsx');
    }

    public function exportPdf(Request $request)
    {
        switch ($request->frequency) {
            case 'yearly':
                $formPettyCashes = FormPettyCash::whereYear('date', $request->year)->orderBy('date', 'DESC')->get();
                break;

            case 'monthly':
                $formPettyCashes = FormPettyCash::whereYear('date', $request->year)->whereMonth('date', $request->month)->orderBy('date', 'DESC')->get();
                break;

            case 'daily':
                $formPettyCashes = FormPettyCash::whereDate('date', $request->date)->orderBy('date', 'DESC')->get();
                break;

            default:
                $formPettyCashes = FormPettyCash::orderBy('date', 'DESC')->get();
                break;
        }

        if ($request->date) {
            $request->date =  Carbon::parse($request->date)->translatedFormat('d F Y');
        }
        if ($request->frequency == 'monthly') {
            $request->month = Carbon::createFromDate($request->year, $request->month, 1)->translatedFormat('F');
        }

        $formPettyCashes->load('details', 'status');
        $totalAmount = $formPettyCashes->sum('amount');
        $pdf = PDF::loadview('pdf.form_petty_cashes', [
            'formPettyCashes' => $formPettyCashes,
            'request' => $request,
            'totalAmount' => $totalAmount
        ])->setPaper('a4', 'landscape');
        return $pdf->stream('Semua Form Petty Cash.pdf');
    }

    public function exportSinglePdf(FormPettyCash $formPettyCash)
    {
        $substr = env('APP_URL');
        $pathArray = [];
        foreach ($formPettyCash->users as $user) {
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
        $formPettyCash->load('details', 'details.budgetCode');
        $pdf = PDF::loadview(
            'pdf.form_petty_cash_single',
            [
                'formPettyCash' => $formPettyCash,
                'pathArray' => $pathArray
            ]
        )->setPaper('a4', 'portrait');
        return $pdf->stream('Form Petty Cash ' . $formPettyCash->number . '.pdf');
    }
}
