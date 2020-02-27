<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    //
    protected $table = 'form_sumbissions';
    protected $primaryKey = 'user_id';
    protected $fillable=['user_id','date','used','balanced','allocation','notes'];
}
