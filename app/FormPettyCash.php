<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormPettyCash extends Model
{
    protected $table = 'form_petty_cashes';

    protected $fillable = [
        'user_id',
        'date',
        'allocation',
        'amount',
        'is_confirmed_pic',
        'is_confirmed_manager_ops',
        'is_confirmed_cashier'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function detail()
    {
        return $this->hasMany('App\FormPettyCashDetail');
    }
}
