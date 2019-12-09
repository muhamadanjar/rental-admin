<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model{
    protected $table = 'reviews';
    protected $fillable = ['date','user_id','driver_id'];
    public function driver(){
        return $this->hasOne(User::Class,'driver_id');
    }
    public function user(){
        return $this->hasOne(User::Class,'user_id');
    }
}
