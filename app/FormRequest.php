<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormRequest extends Model
{
    protected $table = 'form_requests';

    protected $fillable = [
        'user_id',
        'date',
        'method',
        'allocation',
        'amount',
        'attachment',
        'notes'
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
