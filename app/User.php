<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable
{
    use Notifiable,HasApiTokens;
    protected $fillable = [
        'username','name', 'email', 'password',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function user_metas()
    {
        return $this->hasMany(Config::get("consyst.model_namespace") . "\UserMeta","meta_users","id")->whereNotIn('meta_key', ['DOKUMEN_PRIBADI']);

    }

    public function user_images()
    {
        return $this->hasMany(Config::get("consyst.model_namespace") . "\UserMeta","meta_users","id")->whereMetaKey('DOKUMEN_PRIBADI');

    }
}
