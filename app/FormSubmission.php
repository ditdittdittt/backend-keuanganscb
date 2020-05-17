<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    //
    protected $table = 'form_submissions';
    protected $fillable = [
        'user_id',
        'form_request_id',
        'date',
        'used',
        'balance',
        'allocation',
        'notes',
        'is_confirmed_pic',
        'is_confirmed_verificator',
        'is_confirmed_head_dept',
        'is_confirmed_head_office'
    ];
    public function users()
    {
        return $this->belongsToMany('App\User', 'form_submission_has_users', 'form_submission_id', 'user_id')->withPivot(['attachment', 'role_name', 'id']);
    }

    public function formRequest()
    {
        return $this->belongsTo('App\FormRequest');
    }

    public function status()
    {
        return $this->belongsTo(FormStatus::class);
    }

    public function pic()
    {
        return $this->users()->wherePivot('role_name', 'pic');
    }

    public function verificator()
    {
        return $this->users()->wherePivot('role_name', 'verificator');
    }

    public function head_dept()
    {
        return $this->users()->wherePivot('role_name', 'head_dept');
    }

    public function head_office()
    {
        return $this->users()->wherePivot('role_name', 'head_office');
    }

    public function details()
    {
        return $this->hasMany(FormSubmissionDetail::class);
    }

    public function saveWithoutEvents(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }
}
