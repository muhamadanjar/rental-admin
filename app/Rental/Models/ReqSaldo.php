<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;
use App\User as UserAnggota;
use File;
class ReqSaldo extends Model
{
    //
    protected $table = 'request_saldo';
    protected $fillable = ['status'];
    public $timestamps = false;

    protected $locationImages = 'storage/uploads/bukti';
    protected $defaultImages = 'bangunan.jpg';

    public function user(){
        return $this->belongsTo("App\User","req_user_id","id");
    }
    public function getPath(){
        return public_path($this->locationImages).DIRECTORY_SEPARATOR;
    }
    public function getPermalink(){
        return url($this->locationImages).DIRECTORY_SEPARATOR;
    }
    public function getLocationImages(){
        return $this->locationImages;
    }

    public function getPathFullAttribute(){
        if($this->attributes['req_file'] !== NULL){
            if(File::exists($this->getPath().$this->attributes['req_file'])){
                return $this->getPermalink().$this->attributes['req_file'];
            }else{
                return 'http://placehold.it/500';
            }
        }else{
            return 'http://placehold.it/500';
        }
    }
}
