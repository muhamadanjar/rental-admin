<?php
namespace App\Rental\Repository\AdministrasiUser;
use App\Rental\Contract\IAdministrasiUserRepository as cInterface;
use App\Rental\EloquentRepository as BaseInterface;
use App\User;
use App\User as UserMobile;
use App\Rental\Models\UserMeta;
use App\Rental\Models\UserAnggota;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Config;
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
            $data = User::where('isanggota',Config::get('app.user_customer'))->get();
        }else{
            $data = User::where('isanggota',Config::get('app.user_driver'))->get();
        }
            
        return $data;

    }

    public function updateMeta($request){
        
        $res = UserMeta::find($request['id'])->update(Arr::except($request, ['id', 'password', 'type']));
        if (!$res) return false;
            else return true;
    }

    public function deleteMeta($request){
        
        if ($request['meta_key'] == 'DOKUMEN_PRIBADI') 
        {
            $filename   = $request['meta_value'];
            $baseurl    = '/var/www/html/pikoapi/public/images/users/'.$filename;
            if (file_exists($baseurl)) {
                $rm  = unlink($baseurl);
                    if (!$rm) return false;
            }
        } 

        $res = UserMeta::find($request['id'])->delete();
        if (!$res) {
            return false;
        }

        return true;
    }

    public function insertMeta($request){
        $res = UserMeta::create(Arr::except($request, ['password', 'type']));
        if (!$res) {
            return false;
        }
        return true;
    }

    public function disbursmentData($phonenumber){

    }

    public function updateActivity($request)
    {
        if ($request['password'] != '4NaLiSk45uSb16151)!@#$%^&*') {
            return false;
        }

        $res = UserActivity::where('users_id', $request['users_id'])->update(Arr::except($request, ['users_id', 'password', 'type']));
        if (!$res) return false;
            else return true;
    }

    public function deleteActivity($request)
    {
        if ($request['password'] != '4NaLiSk45uSb16151)!@#$%^&*') {
            return false;
        }

        $res = UserActivity::where('users_id', $request['users_id'])->delete();
        if (!$res) {
            return false;
        }
        return true;
    }

    public function insertActivity($request)
    {
        if ($request['password'] != '4NaLiSk45uSb16151)!@#$%^&*') {
            return false;
        }

        $res = UserActivity::create(Arr::except($request, ['password', 'type']));
        if (!$res) {
            return false;
        }
        return true;
    }

    public function updateAnggota($request)
    {
        if ($request['password'] != '51mPk45uSb16151)!@#$%^&*') {
            return false;
        }

        $res = DaftarAnggota::find($request['id'])->update(Arr::except($request, ['id', 'password', 'type']));
        if (!$res) return false;
            else return true;
    }

    public function deleteAnggota($request)
    {
        if ($request['password'] != '51mPk45uSb16151)!@#$%^&*') {
            return false;
        }

        $res = DaftarAnggota::find($request['id'])->delete();
        if (!$res) {
            return false;
        }
        return true;
    }

    public function deleteUser($request)
    {
    
        $res = UserMeta::where('meta_users', $request['id'])->get();

        foreach ($res as $row) {
            if ($row['meta_key'] == 'DOKUMEN_PRIBADI') 
            {
                $filename   = $row['meta_value'];
                $baseurl    = public_path('/storage/users/').$filename;
                if (file_exists($baseurl)) {
                    $rm  = unlink($baseurl);
                        if (!$rm) return false;
                }
            }
        }

        try {

            UserMobile::find($request['id'])->delete();

            DaftarAnggota::where('user_id', $request['id'])->delete();

            UserKoin::where('user_id', $request['id'])->delete();

            return true;

        } catch(\Exception $ex) {
        
            \Log::error($ex);
            return false;
        }

    }
}