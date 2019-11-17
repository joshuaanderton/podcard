<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AccountUser extends Model
{
    protected $fillable = [
        'account_id',
        'user_id',
        'invite_token',
        'invite_email'
    ];

    public $timestamps = false;
}
