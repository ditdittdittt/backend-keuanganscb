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
        'nominal'
    ];

    public function pettyCash()
    {
        return $this->belongsTo('App\FormPettyCash');
    }
}
