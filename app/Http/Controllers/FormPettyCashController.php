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
            $formPettyCashes = FormPettyCash::all();
            $formPettyCashes->load(['user', 'details', 'details.budgetCode', 'status']);
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
            $formPettyCash->load(['details', 'user', 'details.budgetCode', 'status']);
            return ReturnGoodWay::successReturn(
                $formPettyCash,
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

        $formPettyCashes->load('user', 'details', 'status');
        $total = $formPettyCashes->sum('amount');
        // return response()->json($formPettyCashes);
        $pdf = PDF::loadview('pdf.form_petty_cashes', [
            'formPettyCashes' => $formPettyCashes,
            'request' => $request,
            'total' => $total
        ])->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function exportSinglePdf(FormPettyCash $formPettyCash)
    {
        $formPettyCash->load('user', 'details', 'details.budgetCode');
        $pdf = PDF::loadview(
            'pdf.form_petty_cash_single',
            [
                'formPettyCash' => $formPettyCash
            ]
        )->setPaper('a4', 'portrait');
        return $pdf->stream();
    }
}
