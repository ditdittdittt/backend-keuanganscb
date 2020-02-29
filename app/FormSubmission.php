<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    //
    protected $table = 'form_submissions';
    protected $fillable=[
        'user_id',
        'date',
        'used',
        'balanced',
        'allocation',
        'notes',
        'is_confirmed_pic',
        'is_confirmed_verificator',
        'is_confirmed_head_dept',
        'is_confirmed_head_office'
    ];
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function formRequest(){
        return $this->belongsTo('App\FormRequest');
    }
}
