<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;
use File;
class Promo extends Model
{
    protected $table = 'sys_ms_promo';
    protected $primaryKey = 'id';
    protected $dates = ['tgl_mulai','tgl_akhir'];

    public function getPermalink(){
        return url('storage/uploads/promo').DIRECTORY_SEPARATOR;
    }
    public function getPath(){
        
        return public_path('storage/uploads/promo').DIRECTORY_SEPARATOR;
    }

    public function getImagePathAttribute(){
        
        if($this->attributes['foto'] !== NULL){
            if(File::exists($this->getPath().$this->attributes['foto'])){
                return $this->getPermalink().$this->attributes['foto'];
            }else{
                return 'http://placehold.it/320x160';
            }
        }else{
            return 'http://placehold.it/320x160';
        }
        
    }
}
