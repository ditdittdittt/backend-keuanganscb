<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormSubmissionUsers extends Model
{
    protected $table = 'form_submission_has_users';

    protected $fillable = [
        'role_id',
        'user_id',
        'form_submission_id',
        'attachment'
    ];
}
