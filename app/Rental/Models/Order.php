<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;
use App\User as UserAnggota;
class Order extends Model
{
    protected $table = 'm_order';
    protected $primaryKey = 'order_id';
    public $timestamps = false;
    const STATUS_PENDING = 0;
    const STATUS_RECEIVE_DRIVER = 1;
    const STATUS_ONTHEWAY_DRIVER = 2;
    const STATUS_INTRANSIT = 3;
    const STATUS_COMPLETE = 4;
    const STATUS_CANCELED = 5;
    const STATUS_DECLINE = 6;


    public function driver(){
        return $this->belongsTo(UserAnggota::class,'order_driver_id','id')->where('isanggota',1);
    }

    public function customer(){
        return $this->belongsTo(UserAnggota::class,'order_user_id','id')->where('isanggota',2);
    }

    public function getStatusAttribute(){
        $t = $this->attributes['order_status'];
        if($t == self::STATUS_PENDING){
            $a = 'Pending';
        }else if($t == self::STATUS_RECEIVE_DRIVER){
            $a = 'Menerima Pesanan';
        }else if($t == self::STATUS_ONTHEWAY_DRIVER){
            $a = 'Driver OTW';
        }else if($t == self::STATUS_INTRANSIT){
            $a = 'Sedang Mengantar';
        }else if($t == self::STATUS_COMPLETE){
            $a = 'Selesai';
        }else{
            $a = 'Di tolak';
        }
        return $a;
    }
}
