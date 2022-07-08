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
        Schema::create('TS', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id');
            $table->integer('id_ts');
            $table->integer('vid_TS')->nullable();
            $table->integer('kol_gruz_TS')->nullable();
            $table->integer('kol_TS_TS')->nullable();
            $table->integer('rasstojanie_TS')->nullable();
            $table->integer('ob_ves_TS')->nullable();
            $table->integer('ob_ob_TS')->nullable();
            $table->integer('checked2')->nullable();
            $table->text('stavka_TS')->nullable();
            $table->text('stavka_TS_za_km')->nullable();
            $table->text('stavka_kp_TS')->nullable();
            $table->text('marja_TS')->nullable();
            $table->text('adres_pogruzki_TS')->nullable();
            $table->text('adres_vygr_TS')->nullable();
            $table->text('kommentari_TS')->nullable();
            $table->text('terminal_TS')->nullable();
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
        Schema::dropIfExists('TS');
    }
};
