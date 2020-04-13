<?php

namespace App\Exports;

use App\FormRequest;
use Maatwebsite\Excel\Concerns\FromCollection;

class FormRequestExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return FormRequest::all();
    }
}
