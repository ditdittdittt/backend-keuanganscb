<?php

namespace App\Exports;

use App\FormSubmission;
use Maatwebsite\Excel\Concerns\FromCollection;

class FormSubmissionExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return FormSubmission::all();
    }
}
