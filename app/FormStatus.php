<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormStatus extends Model
{
    protected $table = 'form_status';

    protected $fillable = ['status'];

    public function formRequest()
    {
        return $this->hasMany(FormRequest::class);
    }

    public function formPettyCash()
    {
        return $this->hasMany(FormPettyCash::class);
    }
}
