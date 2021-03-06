<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormPettyCashDetail extends Model
{
    protected $table = 'form_petty_cash_details';

    protected $fillable = [
        'form_petty_cash_id',
        'budget_code',
        'budget_name',
        'nominal',
        'budget_code_id'
    ];

    public function pettyCash()
    {
        return $this->belongsTo('App\FormPettyCash');
    }

    public function budgetCode()
    {
        return $this->belongsTo(BudgetCode::class);
    }
}
