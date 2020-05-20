<?php

namespace App\Exports;

use App\FormRequest;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FormRequestExport implements FromView
{
    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        switch ($this->request->frequency) {
            case 'yearly':
                $formRequests = FormRequest::whereYear('date', $this->request->year)->orderBy('date', 'DESC')->get();
                break;
            case 'monthly':
                $formRequests = FormRequest::whereYear('date', $this->request->year)->whereMonth('date', $this->request->month)->orderBy('date', 'DESC')->get();
                break;

            case 'daily':
                $formRequests = FormRequest::whereDate('date', $this->request->date)->orderBy('date', 'DESC')->get();
                break;

            default:
                $formRequests = FormRequest::orderBy('date', 'DESC')->get();
                break;
        }
        $formRequests->load('users');
        if ($this->request->date) {
            $this->request->date =  Carbon::parse($this->request->date)->translatedFormat('d F Y');
        }
        if ($this->request->frequency == 'monthly') {
            $this->request->month = Carbon::createFromDate($this->request->year, $this->request->month, 1)->translatedFormat('F');
        }

        $totalAmount = $formRequests->sum('amount');
        return view(
            'excel.form_requests',
            [
                'formRequests' => $formRequests,
                'request' => $this->request,
                'totalAmount' => $totalAmount
            ]
        );
    }
}
