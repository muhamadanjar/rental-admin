<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request as httpRequest;
use Auth;
use Request;
use PDOException;
use Validator;
use Gate;
use App\Rental\Misc\RentalHelper;
use App\Rental\EloquentRepository as BaseRepository;
use Yajra\Datatables\Facades\Datatables;


class MainCtrl extends Controller
{
    protected $request;
    protected $repository;
    protected $data=[];
    protected $param=[];
    protected $key;
}
