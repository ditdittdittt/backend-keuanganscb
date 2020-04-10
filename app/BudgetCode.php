<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetCode extends Model
{
    protected $table = 'budget_codes';

    protected $fillable = [
        'code',
        'name',
    ];

    public function formPettyCashDetails()
    {
        return $this->hasMany(FormPettyCashDetail::class);
    }
}
