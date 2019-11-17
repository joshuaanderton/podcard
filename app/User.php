<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cookie;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function accounts()
    {
        return $this->hasManyThrough(
            'App\Account',
            'App\AccountUser',
            'user_id',
            'id',
            'id',
            'account_id'
        );
    }

    public function currentAccount()
    {
        $current_account_id = Cookie::get('current_account');
        if ($current_account_id && $this->accounts()->where('id', $current_account_id)->exists()) :
            $current_account = $this->accounts()->find($current_account_id);
        else :
            $current_account = $this->accounts()->first();
        endif;
        return $current_account;
    }
}
