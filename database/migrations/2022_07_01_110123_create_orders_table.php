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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->text('data_vneseniya')->nullable();
            $table->text('rasschitat_do')->nullable();
            $table->text('nomenklatura')->nullable();
            $table->text('nomer_zayavki')->nullable();
            $table->text('kompaniya_zakazchik')->nullable();
            $table->text('menedzer_zakazchik')->nullable();
            $table->text('ISD')->nullable();
            $table->text('cena_kontrakta')->nullable();
            $table->text('data_kontrakta')->nullable();
            $table->text('adres_pogruzke')->nullable();
            $table->text('data_pogruzki')->nullable();
            $table->text('data_dostavki')->nullable();
            $table->text('adres_vygruski')->nullable();
            $table->text('komment_1')->nullable();
            $table->integer('logist')->nullable();
            $table->integer('gruzomesta_big')->nullable();
            $table->integer('gruzomesta_small')->nullable();
            $table->integer('rasstojanie')->nullable();
            $table->integer('ob_ves')->nullable();
            $table->integer('ob_ob')->nullable();
            $table->integer('vid_perevozki')->nullable();

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
        Schema::dropIfExists('orders');
    }
};
