<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormRequestDetail extends Model
{
    protected $table = "form_request_details";

    protected $fillable = [
        'form_request_id',
        'budget_code_id',
        'nominal'
    ];

    public function formRequest()
    {
        return $this->belongsTo('App\FormRequest');
    }

    public function budgetCode()
    {
        return $this->belongsTo(BudgetCode::class);
    }
}
