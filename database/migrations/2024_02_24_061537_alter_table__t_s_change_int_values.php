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
        Schema::table('TS', function (Blueprint $table) {
            $table->text('kol_gruz_TS')->nullable()->change();
            $table->text('kol_TS_TS')->nullable()->change();
            $table->text('rasstojanie_TS')->nullable()->change();
            $table->text('ob_ves_TS')->nullable()->change();
            $table->text('ob_ob_TS')->nullable()->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('TS', function (Blueprint $table) {
            //
        });
    }
};
