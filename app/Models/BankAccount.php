<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'balance', 'credit_limit'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'credit_limit'];

    /**
     * Casts bank account attributes to proper types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'balance' => 'float'
    ];
}
