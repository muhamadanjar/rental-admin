<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class ApiCtrl extends Controller{
    private $main_user;
    public function __construct(){
        $this->main_user = Auth::guard('api');
    }

    public function getResourceOwnerID()
    {
        return $this->main_user->id;
    }
}
