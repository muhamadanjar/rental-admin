<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'm_notification';
    // protected $fillable = ['notif_from','notif_date','message','status'];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
