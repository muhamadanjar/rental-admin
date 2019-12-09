<?php

namespace App\Rental\Auth;

use App\Rental\Models\Cabang;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Rental\Auth\Contracts\IUserRepository as UserInterface;
use App\Rental\EloquentRepository as BaseInterface;
use App\Rental\Models\Grup;
use App\Rental\Models\Menu;
use App\Rental\Models\User;
use App\Rental\Models\Reference;
use Request;
use Hash;
use App\Rental\Models\Akses;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;


class UserRepository extends BaseInterface implements UserInterface {
	public function __construct() {
		parent::__construct(new User());
		$this->tbName = Config::get('Rental.user.table');
	}

	
	public function getAuthPassword() {
		return $this->password;
	}

	
	public function doLogin() {
		if (Auth::check()) {
			$usr = Auth::user();
			$usr->login_terakhir = Carbon::now();
			$usr->login_ip = request()->ip();
			$usr->save();
			$client = new \GuzzleHttp\Client([
            						'headers' => [ 
            							'Content-Type' => 'application/json', 
            							'App-ID' => '5a56c61870db3', 
            							'App-Key' => 'een9K8k2yJQh3rkmPs6Q31aT31RsFM0C' 
            						]
        	]);
        	$response = $client->post('https://api.piko.id:5000/login',
	            ['body' => json_encode(
	                [
	                    'phonenumber' => "admin@piko.id",
	                    'password' => "ksusb123",
	            	]),
		        	'verify' => false
	        	]
	        );
	        $token = json_decode($response->getBody()->getContents());
			session()->put('accessToken', $token->accessToken);
		}
	}

	
	public function doLogout() {
		if (Auth::check()) {
			// set login field to 0
			$usr = $this->find(Auth::user()->id_user);
			$usr->login = 0;
			$usr->save();
		}
	}
	public function getGroupId() {
		if (Auth::check()) {
			// set login field to 0
			return Auth::user()->find(Auth::id())->grups()->first();
		}
	}
	public function getPermissions() {
		return $this->model->akses();
	}

	/**
	 * Change status.
	 *
	 * @param int $id    preference id
	 * @param int $value beetween 0 to 1, 1 for aktive and 0 for non active
	 *
	 * @return mixed
	 */
	public function changeStatus($id, $value) {
		return \DB::table($this->tbName)
			->where('id_user', $id)
			->update(['status' => $value]);
	}
	/**
	 * Showing data with join table.
	 *
	 * @return mixed
	 */
	public function showData() {
	    return $this->model->with('cabang');

	}
	/**
	 * Get refrence table for combobox.
	 *
	 * @return object array of object
	 */
	public function getReference() {
		return (object) array(
			'grup' => Grup::all()->pluck('FullName', 'id_grup'),
            'cabang' => Cabang::all()->pluck('FullName', 'id_cabang'),
		);
	}
	/**
	 * Get Group of specifict user.
	 *
	 * @param string @id user id to find
	 *
	 * @return mixed
	 */
	public function getGroups($id) {

		return $this->model->find($id)
			->grups()->get();
	}

	public function Store($data, $group) {
		try {
			$model = $this->model->newInstance();
			$model->fill($data);

			\DB::transaction(function () use ($model, $group) {
				$model->save();
				$model->grups()->sync($group);
			});
			$this->resetModel();

			return 1;
		} catch (Exception $e) {
			$this->resetModel();

			return 0;
		}
	}
	
	public function UpdateX($data, $group, $id) {
		
		$model = $this->model->newInstance();
		$model = $model->findOrFail($id);

		$model->fill($data);

        try {
			\DB::transaction(function () use ($model, $group) {
				$model->save();
				$model->grups()->sync($group);
			});
			$this->resetModel();
            DB::commit();
			return 1;
		} catch (Exception $e) {

            DB::rollBack();
			$this->resetModel();
			return 0;
		}
	}

	/**
	 * Delete a entity in repository by id overide base repo.
	 *
	 * @param  $id
	 *
	 * @return int
	 */
	public function delete($id) {
		try {
			$model = $this->find($id);
			$originalModel = clone $model;
			$this->resetModel();
			$deleted = $model->delete();
			$originalModel->grups()->detach();

			return $deleted;
		} catch (Exception $e) {
			$this->resetModel();

			return;
		}
	}
	/**
	 * get akses for combobox.
	 *
	 * @return array  all akses
	 */
	public function getPerm() {
		return Akses::all()->pluck('nama', 'id_akses')->all();
	}
	/**
	 * Get list akses by id user
	 * @method getPermById
	 * @author Bagus Wahyu Aprianto S.Kom
	 * @param  integer      $id user id
	 * @return array        permission list of curent user
	 */
	public function getPermById($id) {
		$model = new User();

		return $model->find($id)->akses()->get()->pluck('id_akses')->all();
	}
	
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
		return Menu::all()->pluck('nama_menu', 'id_menu')->all();
	}
	
	public function getMenuById($id) {
		$model = new User();
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

	public function checkPassAuthor($pass) {
		//$model = User::whereRaw('password_otorisasi = ? and id= ?', array(Hash::make($pass), Auth::id()))->first();
		if (Hash::check($pass,Auth::User()->password_otorisasi))
		{
			return true;

		}else{
			return false;
		}


	}

	public function getMainMenu($id)
    {
        return $this->model->where('id_user',$id)->with(['menus' => function ($query) {
            $query->where('status',1)->where('menu_grup',0)->orderBy('urutan', 'ASC');
        }])->get()->toArray();
    }

    public function getChildMenu($id,$gid)
    {
        return $this->model->where('id_user',$id)->with(['menus' => function ($query) use($gid) {
            $query->where('status',1)->where('menu_grup',$gid)->orderBy('urutan', 'ASC');
        }])->get()->toArray();
    }
	
	public function getUser() {
        $usercab = Auth::user()->with('cabang')->findOrFail(Auth::user()->id_user)->cabang->id_cabang;
        $username = Auth::user()->username;

        if($usercab=="000" || $usercab=="001") {
            return $this->model->with(['cabang' => function ($query) {
                $query->select('id_cabang', 'nama_cabang');
            }])->select('id_user', 'username','nama_user','kode_cabang','status')->get();
	}else {
            return $this->model->with(['cabang' => function ($query) {
                $query->select('id_cabang', 'nama_cabang');
            }])->select('id_user', 'username','nama_user','kode_cabang','status')
                ->where('kode_cabang',$usercab)
                ->where('username',$username)
                ->get();
        }
    }

	public function getCabang($type) {
		return (object) array(
            'cabang' => Cabang::where('jenis', $type)->pluck('nama_cabang', 'id_cabang'),
		);
	}

	public function getBranchType($branch) {
		if( !empty($branch) ) {
			return  Cabang::select('jenis')->where('id_cabang', $branch)->first();
		}
	}

	public function getDownloaded() {
		$res = Reference::where("kode_ref", "PIKO_DOWNLOADED")->first();
		return $res->nama_ref;
	}

	public function getUsersChart() {
		$stmt = "SELECT 'DECLINE' as status, count(*) as jumlah FROM users WHERE status = '3'
					UNION
				SELECT 'APPROVED' as status, count(*) as jumlah FROM  m_users_account where id not in (321,404)
					UNION
				SELECT 'HAS LOAN' as status, count( DISTINCT users_id) as jumlah FROM m_loan_submission
					WHERE no_pinjaman_artos IS NOT NULL and users_id not in (388)
					UNION
				SELECT 'TRANSFER ONLINE' as status, COUNT(*) as jumlah FROM sys_tr_anggota
					UNION
				SELECT 'TESTING' as status, COUNT(*) as jumlah FROM users where users.status != '3'
				AND id not in (388, 2922)
				AND id not in (SELECT user_id FROM  m_users_account where id not in (321,404))
				AND id not in (SELECT user_id FROM sys_tr_anggota)";
		return DB::select(DB::raw($stmt));
	}

	public function userTotalChart() {
		$stmt = "SELECT COUNT(*) as total from users where id not in (388, 2922)
					UNION
					SELECT SUM(plafond) as total FROM  m_users_account where id not in (321,404)";
		return DB::select(DB::raw($stmt));
	}

	public function userLoanChart() {
		$stmt = "SELECT 'PINJAMAN DANA' as status, count(nofas) as jumlah FROM m_loan_submission 
				where jenis = 'PINJAMAN DANA' and status = '3' and no_pinjaman_artos IS NOT NULL
					union
				SELECT 'PINJAMAN BARANG' as status, count(nofas) as jumlah FROM m_loan_submission 
				where jenis = 'PINJAMAN BARANG' and status = '3' and no_pinjaman_artos IS NOT NULL";
		return DB::select(DB::raw($stmt));
	}

	public function userLoanTotalChart() {
		$stmt = "select 
				sum(tbl1.nilai_pokok) as total
			from (select nofas, sum(nilai_pokok) nilai_pokok from sys_ref_credits where 
				nofas in (select nofas from m_loan_submission where no_pinjaman_artos IS NOT NULL and jenis = 'PINJAMAN DANA' and status='3')
			GROUP BY nofas) as tbl1
			UNION
			select 
				sum(tbl1.nilai_pokok) as total
			from (select nofas, sum(nilai_pokok) nilai_pokok from sys_ref_credits where 
				nofas in (select nofas from m_loan_submission where no_pinjaman_artos IS NOT NULL and jenis = 'PINJAMAN BARANG' and status='3')
			GROUP BY nofas) as tbl1";
		return DB::select(DB::raw($stmt));
	}

	public function userOutstandingChart() {
		$stmt = "select
					'DANA' as status,
					sum(tbl1.nilai_pokok) as jumlah
				from (select nofas, sum(nilai_pokok) nilai_pokok from sys_ref_credits where 
					nofas in (select nofas from m_loan_submission where no_pinjaman_artos IS NOT NULL and jenis = 'PINJAMAN DANA' and status='3')
					and tgl_bayar is null
				group by nofas) as tbl1
				union
				select
					'PAID DANA' as status,
					sum(tbl1.nilai_pokok) as jumlah
				from (select nofas, sum(nilai_pokok) nilai_pokok from sys_ref_credits where 
					nofas in (select nofas from m_loan_submission where no_pinjaman_artos IS NOT NULL and jenis = 'PINJAMAN DANA' and status='3')
					and tgl_bayar is not null
				group by nofas) as tbl1
				union
				select
					'BARANG' as status,
					sum(tbl1.nilai_pokok) as jumlah
				from (select nofas, sum(nilai_pokok) nilai_pokok from sys_ref_credits where 
					nofas in (select nofas from m_loan_submission where no_pinjaman_artos IS NOT NULL and jenis = 'PINJAMAN BARANG' and status='3')
					and tgl_bayar is null
				group by nofas) as tbl1
				union
				select
					'PAID BARANG' as status,
					sum(tbl1.nilai_pokok) as jumlah
				from (select nofas, sum(nilai_pokok) nilai_pokok from sys_ref_credits where 
					nofas in (select nofas from m_loan_submission where no_pinjaman_artos IS NOT NULL and jenis = 'PINJAMAN BARANG' and status='3')
					and tgl_bayar is not null
				group by nofas) as tbl1";
		return DB::select(DB::raw($stmt));
	}

	public function userTotalOutstandingChart() {
		$stmt = "select
				sum(tbl1.nilai_pokok) as total
			from (select nofas, sum(nilai_pokok) nilai_pokok from sys_ref_credits where 
				nofas in (select nofas from m_loan_submission where no_pinjaman_artos IS NOT NULL and status='3')
				and tgl_bayar is null
			group by nofas) as tbl1
			union
			select
				sum(tbl1.nilai_pokok) as total
			from (select nofas, sum(nilai_pokok) nilai_pokok from sys_ref_credits where 
				nofas in (select nofas from m_loan_submission where no_pinjaman_artos IS NOT NULL and status='3')
				and tgl_bayar is not null
			group by nofas) as tbl1";
		return DB::select(DB::raw($stmt));
	}

	public function userBayarChartDana() {
		$stmt = "select 'PINJAMAN DANA' as status, DATE_FORMAT(tgl_bayar, '%M') as month, DATE_FORMAT(tgl_bayar, '%m') as dates, CAST(SUM(nilai_bayar) as UNSIGNED) as jumlah 
				from sys_ref_credits where tgl_bayar is not null and
				nofas in (select nofas from m_loan_submission where no_pinjaman_artos IS NOT NULL and jenis = 'PINJAMAN DANA')
				GROUP BY DATE_FORMAT(tgl_bayar, '%M'), DATE_FORMAT(tgl_bayar, '%m') ORDER BY dates ASC";
		return DB::select(DB::raw($stmt));
	}

	public function userBayarChartBarang() {
		$stmt = "select 'PINJAMAN BARANG' as status, DATE_FORMAT(tgl_bayar, '%M') as month, DATE_FORMAT(tgl_bayar, '%m') as dates, CAST(SUM(nilai_bayar) as UNSIGNED) as jumlah 
				from sys_ref_credits where tgl_bayar is not null and
				nofas in (select nofas from m_loan_submission where no_pinjaman_artos IS NOT NULL and jenis = 'PINJAMAN BARANG')
				GROUP BY DATE_FORMAT(tgl_bayar, '%M'), DATE_FORMAT(tgl_bayar, '%m') ORDER BY DATE_FORMAT(tgl_bayar, '%m') ASC";
		return DB::select(DB::raw($stmt));
	}

	public function JasaChartDana() {
		$stmt = "select 'PINJAMAN DANA' as status, DATE_FORMAT(tgl_bayar, '%M') as month, DATE_FORMAT(tgl_bayar, '%m') as dates, CAST(SUM(nilai_bunga) as UNSIGNED) as jumlah 
				from sys_ref_credits where tgl_bayar is not null and
				nofas in (select nofas from m_loan_submission where no_pinjaman_artos IS NOT NULL and jenis = 'PINJAMAN DANA')
				GROUP BY DATE_FORMAT(tgl_bayar, '%M'), DATE_FORMAT(tgl_bayar, '%m') ORDER BY dates ASC";
		return DB::select(DB::raw($stmt));
	}

	public function JasaChartBarang() {
		$stmt = "select 'PINJAMAN BARANG' as status, DATE_FORMAT(tgl_bayar, '%M') as month, DATE_FORMAT(tgl_bayar, '%m') as dates, CAST(SUM(nilai_bunga) as UNSIGNED) as jumlah 
				from sys_ref_credits where tgl_bayar is not null and
				nofas in (select nofas from m_loan_submission where no_pinjaman_artos IS NOT NULL and jenis = 'PINJAMAN BARANG')
				GROUP BY DATE_FORMAT(tgl_bayar, '%M'), DATE_FORMAT(tgl_bayar, '%m') ORDER BY DATE_FORMAT(tgl_bayar, '%m') ASC";
		return DB::select(DB::raw($stmt));
	}
}
