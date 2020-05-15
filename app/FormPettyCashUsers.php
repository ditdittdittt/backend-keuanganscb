<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormPettyCashUsers extends Model
{
    protected $table = 'form_petty_cash_has_users';

    protected $fillable = [
        'role_id',
        'user_id',
        'form_petty_cash_id',
        'attachment'
    ];
}
