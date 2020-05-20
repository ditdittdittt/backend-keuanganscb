<?php

namespace App\Exports;

use App\FormPettyCash;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class FormPettyCashExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        switch ($this->request->frequency) {
            case 'yearly':
                $formPettyCashes = FormPettyCash::whereYear('date', $this->request->year)->orderBy('date', 'DESC')->get();
                break;

            case 'monthly':
                $formPettyCashes = FormPettyCash::whereYear('date', $this->request->year)->whereMonth('date', $this->request->month)->orderBy('date', 'DESC')->get();
                break;

            case 'daily':
                $formPettyCashes = FormPettyCash::whereDate('date', $this->request->date)->orderBy('date', 'DESC')->get();
                break;

            default:
                $formPettyCashes = FormPettyCash::orderBy('date', 'DESC')->get();
                break;
        }

        if ($this->request->date) {
            $this->request->date =  Carbon::parse($this->request->date)->translatedFormat('d F Y');
        }
        if ($this->request->frequency == 'monthly') {
            $this->request->month = Carbon::createFromDate($this->request->year, $this->request->month, 1)->translatedFormat('F');
        }

        $formPettyCashes->load('details', 'status');
        $totalAmount = $formPettyCashes->sum('amount');
        
        return view(
            'excel.form_petty_cash', 
            [
                'formPettyCashes' => $formPettyCashes,
                'request' => $this->request,
                'totalAmount' => $totalAmount
            ]
        );
    }
}