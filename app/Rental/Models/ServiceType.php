<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    protected $table = 'sys_ms_service_type';
    protected $primarykey = 'id';
    public $timestamps = false;
}
