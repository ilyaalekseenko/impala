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
        Schema::create('final_grades', function (Blueprint $table) {
            $table->id();
            $table->integer('grade_id');
            $table->integer('id_ts')->nullable();;
            $table->integer('vid_TS')->nullable();
            $table->text('perevozchik')->nullable();
            $table->text('voditel')->nullable();
            $table->integer('nomer_TS')->nullable();
            $table->integer('nomer_PP')->nullable();
            $table->integer('kol_gruz_TS')->nullable();
            $table->integer('rasstojanie_TS')->nullable();
            $table->integer('ob_summa')->nullable();
            $table->integer('NDS_check')->nullable();
            $table->integer('predoplata')->nullable();
            $table->integer('TN_check')->nullable();
            $table->integer('checked2')->nullable();
            $table->integer('terminal_TS')->nullable();
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
        Schema::dropIfExists('final_grades');
    }
};
