<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\AuditTrail\Loggable;
use App\AuditTrail\RevisionableTrait;

class Mobil extends Model
{
    //use SoftDeletes;
    // use RevisionableTrait;
    protected $table = 'sys_ms_car';
    protected $primaryKey = 'id';
    protected $fillable = ['no_plat', 'nama_mobil', 'warna'];
    protected $dates = ['deleted_at'];

    public static $rules = array(
        'no_plat' => 'required|min:1',
        'nama_mobil' => 'required|min:3',
        'warna' => 'required|numeric|min:0',
    );

    public static $rules_driver = array(
		'no_plat' => 'required|min:3',
        // 'merk' => 'required|min:3',
        'warna' => 'required|min:3',
        'nip' => 'required',
        //'harga' => 'required|numeric',
        //'harga_perjam' => 'required|numeric',
        'name'  => 'required',
        'username' => 'required',
        'email' => 'required|email',
        'no_telp' => 'required|numeric',
        'alamat' => 'required',
        // 'deposit' => 'required',
        'mobil_name' => 'required',
        'password' => 'required|confirmed|min:6',
    );

    public static $messages = [
        'no_plat.required' => 'No Ruas harus diisi!',
        'nama_mobil.required' => 'Nama Ruas harus di isi',
        'warna.required' => 'Panjang Jalan harus di isi',
    ];

    public static $messages_driver = [
	    'no_plat.required' => 'No Plat harus diisi!',
        // 'merk.required' => 'Merk harus di isi',
        'warna.required' => 'Warna harus diisi!',
        'nip.required' => 'No KTP harus diisi!',
        //'harga.required' => 'Harga harus di isi',
        //'harga_perjam.required' => 'Harga Perjam harus diisi!',
        'name.required' => 'Nama Driver harus di isi',
        'username.required' => 'Username harus diisi!',
        'email.required' => 'Email harus di isi',
        'no_telp.required' => 'No telp harus di isi',
        'no_telp.numeric' => 'No telp harus nomor',
        'alamat.required' => 'Alamat harus di isi',
        // 'deposit.required' => 'Deposit harus di isi',
        'mobil_name.required' => 'Nama Mobil harus di isi',
        'password.required' => 'Password harus di isi',
        'password.confirmed' => 'Password Tidak sama',
        'password.min' => 'Password Tidak kurang dari 6 karakter'
	];

    public function getPermalink()
    {
        return url('files/uploads/mobil') . DIRECTORY_SEPARATOR . '/';
    }

    public function getPath()
    {
        return public_path('files/uploads/mobil') . DIRECTORY_SEPARATOR . '/';
    }

    public function detil()
    {
        return $this->hasMany(MobilDetil::class, 'mobil_id');
    }

    public function files()
    {
        return $this->hasMany(MobilFile::class,'mobil_id');
    }
    
    public function author(){
        return $this->belongsTo('App\User', 'user_id');
    }

    public function scopeActive($query)
    {
        // $query->select(
        //     DB::raw('SUM(ptjp_aspal) as aspal'),
        //     DB::raw('SUM(ptjp_beton) as beton'),
        //     DB::raw('SUM(ptjp_kerikil) as kerikil'),
        //     DB::raw('SUM(ptjp_tanah) as tanah')
        // );
        return $query->where('status', '<>', 'self::STATUS_DRAFT');

    }


}
