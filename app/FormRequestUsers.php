<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormRequestUsers extends Model
{
    protected $table = 'form_request_has_users';

    protected $fillable = [
        'role_id',
        'user_id',
        'form_request_id',
        'attachment'
    ];
}
