<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use File;
class RentPackage extends Model
{
    protected $table = 'rent_package';
    protected $primaryKey = 'rp_id';
    protected $fillable = ['rp_name', 'rp_total_price', 'rp_miles_km','rp_hour'];
    protected $dates = ['deleted_at'];
    public $timestamps = false;

    public static $rules = array(
        'rp_name' => 'required|min:1',
        'rp_total_price' => 'required|numeric',
        'rp_miles_km' => 'required|numeric',
        'rp_hour' => 'required|numeric'
    );

    public static $messages = [
        'rp_name.required' => 'Nama Rental Package harus diisi!',
        'rp_total_price.required' => 'total harga harus di isi',
        'rp_miles_km.required' => 'Harga Per Km harus di isi',
    ];

    public function scopeActive($query){  
        return $query->where('status', '=', '1');
    }

    public function getImagePathAttribute(){
        
        if($this->attributes['image'] !== NULL){
            if(File::exists($this->getPath().$this->attributes['image'])){
                return $this->getPermalink().$this->attributes['image'];
            }else{
                return 'http://placehold.it/320';
            }
        }else{
            return 'http://placehold.it/320';
        }
        
    }

    public function getPermalink(){
        return url('files/uploads/packages').DIRECTORY_SEPARATOR;
    }

    public function getPath(){
        return public_path('files/uploads/packages').DIRECTORY_SEPARATOR;
    }


}
