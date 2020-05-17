<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormSubmissionDetail extends Model
{
    protected $table = "form_submission_details";

    protected $fillable = [
        'form_submission_id',
        'budget_code_id',
        'used',
        'balance'
    ];

    public function budgetCode()
    {
        return $this->belongsTo(BudgetCode::class);
    }
}
