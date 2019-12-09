<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;
use App\User as UserAnggota;
class ReqSaldo extends Model
{
    //
    protected $table = 'request_saldo';
    protected $fillable = ['status'];
    public $timestamps = false;

    public function user(){
        return $this->belongsTo("App\User","user_id","id");
    }
}
