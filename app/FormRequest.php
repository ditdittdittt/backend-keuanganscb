<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormRequest extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'method',
        'allocation',
        'amount',
        'attachment',
        'notes',
        'is_confirmed_pic',
        'is_confirmed_verificator',
        'is_confirmed_head_dept',
        'is_confirmed_cashier'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function status()
    {
        return $this->belongsTo(FormStatus::class);
    }

    public function budgetCode()
    {
        return $this->belongsTo(BudgetCode::class);
    }
}
