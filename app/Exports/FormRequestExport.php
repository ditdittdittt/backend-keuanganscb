<?php

namespace App\Exports;

use App\FormRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FormRequestExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return FormRequest::all();
    }
    
    public function headings(): array
    {
        return ["id", "user_id", "date", "method", "allocation", 
        "amount", "attachment", "notes", "is_confirmed_pic", 
        "is_confirmed_verificator","is_confirmed_head_dept", 
        "is_confirmed_cashier", "bank_name", "bank_code",
        "account_number", "account_owner", "status_id", "budget_code_id", "number"
        ];
    }
}
