<?php

namespace App\Rental\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    protected $table = 'sys_ms_user';
    use Notifiable,HasApiTokens;
    protected $fillable = [
        'username','name', 'email', 'password',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];
}
