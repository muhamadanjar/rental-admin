<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Rental\Models\Mobil;
use Config;
use Laravel\Passport\HasApiTokens;
class User extends Authenticatable{
    use Notifiable,HasApiTokens;
    public $timestamps = true;
    protected $table = 'users';
    protected $fillable = [
        'username','name', 'email', 'password',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $nicename = array(
        'id' => 'ID',
        'phonenumber' => 'Phone Number',
        'email' => 'Email',
        'password' => 'Password',
        'is_admin' => 'Is Admin',
        'isactived' => 'Status'
    );

    public function user_metas()
    {
        return $this->hasMany(Config::get("rental.model_namespace") . "\UserMeta","meta_users","id");

    }

    public function user_images()
    {
        return $this->hasMany(Config::get("rental.model_namespace") . "\UserMeta","meta_users","id")->whereMetaKey('DOKUMEN_PRIBADI');
    }
    public function mobil(){
        return $this->hasMany(Config::get("rental.model_namespace") . "\Mobil","user_id","id")->orderBy('isselected','ASC');
    }

    public function saldo(){
        return $this->hasOne(Config::get("rental.model_namespace")."\UserSaldo","user_id");
    }
}
