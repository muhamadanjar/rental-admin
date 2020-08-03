<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
class UserSaldo extends Model
{
    protected $table = 'm_user_account';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    public function user(){
        return $this->belongsTo("App\User","user_id");
    }
}
