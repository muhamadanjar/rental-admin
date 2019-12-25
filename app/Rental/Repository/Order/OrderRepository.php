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
}
