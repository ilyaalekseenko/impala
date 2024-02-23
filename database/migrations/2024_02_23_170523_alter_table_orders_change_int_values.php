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
        Schema::table('orders', function (Blueprint $table) {
            $table->text('gruzomesta_big')->nullable()->change();
            $table->text('gruzomesta_small')->nullable()->change();
            $table->text('rasstojanie')->nullable()->change();
            $table->text('ob_ves')->nullable()->change();
            $table->text('ob_ob')->nullable()->change();
            $table->text('cena_kontrakta')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
