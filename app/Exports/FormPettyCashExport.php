<?php

namespace App\Exports;

use App\FormPettyCash;
use Maatwebsite\Excel\Concerns\FromCollection;

class FormPettyCashExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return FormPettyCash::all();
    }
}
