<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
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
