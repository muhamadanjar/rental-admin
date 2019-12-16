<?php
namespace App\Rental\Repository\AdministrasiUser;
use App\Rental\Contract\IAdministrasiUserRepository as cInterface;
use App\Rental\EloquentRepository as BaseInterface;
use App\User;
use App\Rental\Models\UserMeta;
class AdministrasiUserRepository extends BaseInterface implements cInterface{
    protected $parent;
    public function __construct(){
        parent::__construct(new User());
    }

    public function getUserDetail($id) {
        return User::with(array('user_metas' => function($query) {
            $query->orderBy('id', 'ASC');
        }))->whereId($id)->first();
    }

    public function getUserData($type='customer'){
        if ($type == 'customer') {
            $data = User::where('isanggota',2)->get();
        }else{
            $data = User::where('isanggota',1)->get();
        }
            
        return $data;

    }
}