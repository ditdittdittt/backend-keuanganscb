<?php

namespace App\Exports;

use App\FormSubmission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FormSubmissionExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return FormSubmission::all();
    }

    public function headings(): array
    {
        return [
            "id", "form_request_id","user_id", "date", "used", "balance",
            "allocation", "notes", "is_confirmed_pic", "is_confirmed_verificator",
            "is_confirmed_head_dept", "is_confirmed_head_office", "number", "status_id"
        ];
    }
}
