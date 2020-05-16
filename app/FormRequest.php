<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class FormRequest extends Model
{
    use Notifiable;
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

    public function users()
    {
        return $this->belongsToMany('App\User', 'form_request_has_users', 'form_request_id', 'user_id')->withPivot(['attachment', 'role_name', 'id']);
    }

    public function formSubmission()
    {
        return $this->hasOne(FormSubmission::class);
    }

    public function status()
    {
        return $this->belongsTo(FormStatus::class);
    }

    public function budgetCode()
    {
        return $this->belongsTo(BudgetCode::class);
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

    public function cashier()
    {
        return $this->users()->wherePivot('role_name', 'cashier');
    }

    public function details()
    {
        return $this->hasMany(FormRequestDetail::class);
    }

    public function saveWithoutEvents(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }
}
