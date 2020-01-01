<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Review extends Model{
    protected $table = 'reviews';
    protected $fillable = ['date','user_id','driver_id'];
    public $timestamps = false;
    public function driver(){
        return $this->hasOne(User::Class,'id','driver_id');
    }
    public function rider(){
        return $this->hasOne(User::Class,'id','user_id');
    }
}
