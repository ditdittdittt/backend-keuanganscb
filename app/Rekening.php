<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    protected $table = 'rekenings';

    protected $fillable = [
        'bank_code',
        'bank_name',
        'account_number',
        'account_owner'
    ];
}
