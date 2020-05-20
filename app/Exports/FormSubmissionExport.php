<?php

namespace App\Exports;

use App\FormSubmission;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Carbon\Carbon;

class FormSubmissionExport implements FromView
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
                $formSubmissions = FormSubmission::whereYear('date', $this->request->year)
                    ->orderBy('date', 'DESC')
                    ->get();
                break;
            case 'monthly':
                $formSubmissions = FormSubmission::whereYear('date', $this->request->year)->whereMonth('date', $this->request->month)
                    ->orderBy('date', 'DESC')
                    ->get();
                break;

            case 'daily':
                $formSubmissions = FormSubmission::whereDate('date', $this->request->date)
                    ->orderBy('date', 'DESC')
                    ->get();
                break;

            default:
                $formSubmissions = FormSubmission::orderBy('date', 'DESC')
                    ->orderByRaw("CASE WHEN number IS NOT NULL THEN 1 ELSE 2 END")
                    ->get();
                break;
        }
        if ($this->request->date) {
            $this->request->date =  Carbon::parse($this->request->date)->translatedFormat('d F Y');
        }
        if ($this->request->frequency == 'monthly') {
            $this->request->month = Carbon::createFromDate($this->request->year, $this->request->month, 1)->translatedFormat('F');
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

        return view(
            'excel.form_submissions',
            [
                'formSubmissions' => $formSubmissions,
                'request' => $this->request,
                'totalAmount' => $totalAmount
            ]
        );
    }
}
