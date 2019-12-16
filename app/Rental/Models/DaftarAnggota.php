<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;

class DaftarAnggota extends Model
{
    public $timestamps = true;
    protected $table = 'sys_tr_anggota';
    protected $primaryKey = "id";

    public function user_login()
    {
        return $this->hasOne(Config::get("rental.model_namespace") . "\UserMobile","id","user_id");

    }

	public function user_metas()
    {
    	return $this->hasMany(Config::get("rental.model_namespace") . "\UserMeta","meta_users","user_id")->whereNotIn('meta_key', ['DOKUMEN_PRIBADI']);

    }

    public function images_meta()
    {
        return $this->hasMany(Config::get("rental.model_namespace") . "\UserMeta","meta_users","user_id")->where('meta_key', ['DOKUMEN_PRIBADI']);

    }
}
