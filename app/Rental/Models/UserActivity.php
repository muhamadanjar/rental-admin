<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class UserActivity extends Model{
    protected $table = 'm_user_activity';
    protected $primaryKey = 'user_id';
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
