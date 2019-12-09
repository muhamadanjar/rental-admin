<?php

namespace App\Rental\Models;

use Illuminate\Database\Eloquent\Model;

class UserMeta extends Model
{
    protected $table = 'm_users_meta';
    public $timestamps = true;
    protected $fillable = ['id',
            'meta_key',
            'meta_value',
            'meta_users',
        ];
        public $nicename = array(
            'id' => 'ID',
            'meta_key' => 'Meta Key',
            'meta_value' => 'Meta Value',
            'meta_users' => 'Meta User'
        );
    public $rules = array();
    protected $casts = [
        'id' => 'string'
    ];
}
