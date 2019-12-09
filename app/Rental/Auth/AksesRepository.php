<?php namespace App\Rental\Auth;


use App\Rental\Models\Akses;
use App\Rental\EloquentRepository as BaseInterface;
use App\Rental\Auth\Contracts\IAksesRepository as cInterface;
use Illuminate\Support\Facades\Config;


class AksesRepository extends BaseInterface implements cInterface
{

    public function __construct()
    {
        parent::__construct(new Akses());
        $this->tbName = Config::get("Rental.akses.table");
    }



}
