<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MOrder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_order', function (Blueprint $table) {
            $table->integer('kode_order');
            $table->integer('user_id');
            $table->string('address_origin');
            $table->decimal('address_origin_lat');
            $table->decimal('address_origin_lng');
            $table->string('address_destination');
            $table->decimal('address_destination_lat');
            $table->decimal('address_destination_lng');
            $table->integer('driver_id')->nullable();
            $table->string('jenis');
            $table->float('nominal');
            $table->timestamp('tgl_pesanan');
            $table->string('keterangan');
            $table->integer('status');
            $table->string('created_by');
            $table->string('updated_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
