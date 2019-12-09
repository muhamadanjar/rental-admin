<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;
use App\User as UserAnggota;
class Order extends Model
{
    protected $table = 'm_order';
    protected $primarykey = 'order_id';

    public function driver(){
        return $this->belongsTo(UserAnggota::class,'user_id','id')->where('isanggota',1);
    }

    public function customer(){
        return $this->belongsTo(UserAnggota::class,'user_id','id')->where('isanggota',2);
    }
}
