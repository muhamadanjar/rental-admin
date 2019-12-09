<?php

namespace App\Rental\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    use Notifiable,HasApiTokens;
    protected $table = 'sys_ms_user';
    protected $primaryKey = 'id_user';
    protected $fillable = [
        'username','name', 'email', 'password',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function akses()
    {
        return $this->belongsToMany(Config::get('rental.model_namespace') . '\Akses', Config::get('rental.user_akses.table'), Config::get('rental.user_akses.primary_key'), Config::get('rental.user_akses.foreign_key'));
    }

   
    public function menus()
    {
        return $this->belongsToMany(Config::get('rental.model_namespace') . '\Menu', Config::get('rental.user_menu.table'), Config::get('rental.user_menu.primary_key'), Config::get('rental.user_menu.foreign_key'));
    }

   
    public function grups()
    {
        return $this->belongsToMany(Config::get('rental.model_namespace') . '\Grup', Config::get('rental.user_grup.table'), Config::get('rental.user_grup.primary_key'), Config::get('rental.user_grup.foreign_key'));
    }

    public function hasPermission($slug)
    {

        return $this->akses->contains('slug', $slug);

    }
    
}
