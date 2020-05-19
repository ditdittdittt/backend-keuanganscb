<?php

namespace App\Exports;

use App\FormPettyCash;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FormPettyCashExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return FormPettyCash::all();
    }

    public function headings(): array
    {
        return ["id", "user_id", "date", "allocation", 
        "amount", "is_confirmed_pic", "is_confirmed_manager_ops",
        "is_confirmed_cashier", "status_id", "number"
        ];
    }
}