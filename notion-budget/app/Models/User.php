<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    protected $table = 'user';
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar', 
        'google_id', 
        'github_id',
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

}
