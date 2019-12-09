<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
class Trip extends Model{
    protected $table = 'trip';
    protected $primaryKey = 'trip_id';
    public $timestamps = true;
    protected $date = ['created_at','updated_at'];
    const STATUS_PENDING = 0;
    const STATUS_RECEIVE_DRIVER = 1;
    const STATUS_ONTHEWAY_DRIVER = 2;
    const STATUS_INTRANSIT = 3;
    const STATUS_COMPLETE = 4;
    const STATUS_CANCELED = 5;
    const STATUS_DECLINE = 6;

    public function driver(){
        return $this->hasOne(User::class,'id','trip_driver');
    }
    public function rider(){
        return $this->hasOne(User::class,'id','trip_bookby');
    }

    public function scopeDetail($query){
        
        return $query->join('trip_detail', function($join){
            $join->on('trip.trip_id', '=', 'trip_detail.trip_id');
    
        });
    }
    public function getStatusAttribute(){
        $t = $this->attributes['trip_status'];
        if($t == self::STATUS_PENDING){
            $a = 'Pending';
        }else if($t == self::STATUS_RECEIVE_DRIVER){
            $a = 'Menerima Pesanan';
        }else{
            $a = 'Di tolak';
        }
        return $a;
    }
}
