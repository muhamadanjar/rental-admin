<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SysTrAnggota extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('sys_tr_anggota',function($table){
        $table->increments('id');
        $table->integer('user_id');
        $table->string('nama');
        $table->string('daftar_anggota');
        $table->integer('is_anggota');
        $table->timestamp('created_by');
        $table->timestamp('updated_by');
        
        $table->timestamps();

      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExist('sys_tr_anggota');
    }
}
