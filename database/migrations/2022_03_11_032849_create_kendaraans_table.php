<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id();
            $table->string('id_perusahaan');
            $table->string('merk_kendaraan');
            $table->string('no_kendaraan');
            $table->string('jumlah_seat');
            $table->string('no_uji');
            $table->date('exp_uji');
            $table->string('no_kps');
            $table->date('exp_kps');
            $table->date('id_angkutan');
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
        Schema::dropIfExists('kendaraans');
    }
};
