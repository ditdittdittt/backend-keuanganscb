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
        'is_confirmed_cashier',
        'is_paid',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User', 'form_petty_cash_has_users', 'form_petty_cash_id', 'user_id')->withPivot(['attachment', 'role_name', 'id']);
    }

    public function details()
    {
        return $this->hasMany('App\FormPettyCashDetail');
    }

    public function status()
    {
        return $this->belongsTo(FormStatus::class);
    }

    public function pic()
    {
        return $this->users()->wherePivot('role_name', 'pic');
    }

    public function manager_ops()
    {
        return $this->users()->wherePivot('role_name', 'manager_ops');
    }

    public function cashier()
    {
        return $this->users()->wherePivot('role_name', 'cashier');
    }

    public function saveWithoutEvents(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }
}
