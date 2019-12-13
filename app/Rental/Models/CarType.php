<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;

class CarType extends Model
{
    protected $table = 'sys_ms_car_type';

    public function car() {
        return $this->belongsToMany(Car::class, 'car_type')->withTimestamps();
    }

}
