<?php
namespace App\Rental\Repository\Order;
use App\Rental\Contract\IOrderRepository as cInterface;
use App\Rental\EloquentRepository as BaseInterface;
use App\Rental\Models\Order;
use App\User;
use Config;
class OrderRepository extends BaseInterface implements cInterface
{
    protected $parent;

	public function __construct(){
		parent::__construct(new Order());
    }
    
    public function data_pemesanan(){
        $data = Order::orderBy('order_tgl_pesanan','DESC')->get();
        return $data;
    }

    public function getDrivers(){
        return User::where('isavail',1)->whereIsanggota(Config::get('app.user_driver'))->get();
    }

    private function SetTransaction($noref, $userid, $nominal, $nama)
    {
        $nomor_transaksi = "OR002".Carbon::now()->format('dmYh').strtoupper(str_random(2));
        $nomor = UserSaldo::where('user_id', $userid)->first();

        $q1  = Transaction::select('virtual_account')->where('status', '0')
            ->where('noref', $noref)
            ->first();

        if($q1) {
            Transaction::where('status', '0')
                ->where('noref', $noref)->delete();
        }

        $status = "Y";
        try {

            DB::beginTransaction();
                $model = new Transaction();
                $model->nomor_transaksi = $nomor_transaksi;         
                $model->noref           = $noref;          
                $model->nominal         = $nominal;       
                $model->atas_nama       = $nama;     
                $model->kode_transaksi  = "OR002"; 
                $model->ket_transaksi   = "PEMBAYARAN PEMESANAN REGULER";           
                $exec = $model->save();
            DB::commit();

            if(!$exec) {
                DB::rollBack();
                return false;
            }

            return array($nomor_transaksi, $status);

        } catch(\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            return false;
        }
    }
}
