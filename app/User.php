<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use App\Rental\Models\Mobil;
use Config;
class User extends Model{
    public $timestamps = false;
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
}
