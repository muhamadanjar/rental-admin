<?php namespace App\Rental\Auth;

use App\Rental\Models\Grup;
use App\Rental\EloquentRepository as BaseInterface;
use App\Rental\Auth\Contracts\IGrupRepository as cInterface;
use App\Rental\Models\Report;
use Exception;
use Illuminate\Support\Facades\Config;
use App\Rental\Models\Menu;
use App\Rental\Models\Akses;
use Request;
use Illuminate\Support\Facades\DB;


class GrupRepository extends BaseInterface implements cInterface
{

    public function __construct()
    {
        parent::__construct(new Grup());
        $this->tbName = Config::get("Rental.grup.table");
    }
    public function changeStatus($id,$value)
    {
        return \DB::table($this->tbName)
            ->where('id_grup', $id)
            ->update(['status' => $value]);

    }
    public function getPermissions() {
        return $this->model->akses();
    }
    /**
     * get akses for combobox.
     *
     * @return array  all akses
     */
    public function getPerm() {
        return Akses::all()->pluck('nama', 'id_akses')->all();
    }
    
    public function getPermById($id) {
        $model = new Grup();

        return $model->find($id)->akses()->get()->pluck('id_akses')->all();
    }
    /**
     * store akses.
     *
     * @param int   $id   user id
     * @param array $perm array of user permission
     *
     * @return int 1 if succeed and 0 for error
     */
    public function storePerm($id, $perm) {
        try {
            if (!isset($perm)) {
                $perm = array();
            }

            $model = $this->find($id);

            \DB::transaction(function () use ($model, $perm) {
                $model->akses()->sync($perm);
            });
            DB::commit();
            return 1;
        } catch (Exception $e) {

            $this->resetModel();
            DB::rollback();
            return 0;
        }
    }
    
    public function getMenu() {
        return Menu::all()->pluck('name_for_user', 'id_menu')->all();
    }
   
    public function getMenuById($id) {
        $model = new Grup();
        return $model->find($id)->menus()->get()->pluck('id_menu')->all();
    }


   
    public function storeMenus($id, $menus) {
        try {
            if (!isset($menus)) {
                $menus = array();
            }

            $model = $this->find($id);

            \DB::transaction(function () use ($model, $menus) {
                $model->menus()->sync($menus);
            });
            DB::commit();
            return 1;
        } catch (Exception $e) {
            $this->resetModel();
            DB::roolback();
            return 0;
        }
    }
    
    public function storeReports($id, $report) {
        try {
            if (!isset($report)) {
                $report = array();
            }

            $model = $this->find($id);

            \DB::transaction(function () use ($model, $report) {
                $model->reports()->sync($report);
            });
            DB::commit();
            return 1;
        } catch (Exception $e) {
            $this->resetModel();
            DB::roolback();
            return 0;
        }
    }

   
    public function getReport() {
        return Report::all()->pluck('nama', 'id')->all();
    }
    
    
    public function getReportById($id) {
        $model = new Grup();
        return $model->find($id)->reports()->get()->pluck('id')->all();
    }

}
