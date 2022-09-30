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
        Schema::create('grade_pogruzkas', function (Blueprint $table) {
            $table->id();
            $table->integer('grade_id');
            $table->integer('id_ts');
            $table->integer('id_pogruzka');
            $table->integer('pogruzka_or_vygruzka')->default(null);
            $table->text('adres_pogruzki')->nullable();
            $table->string('date_ts')->default('');
            $table->string('time_ts')->default('');

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
        Schema::dropIfExists('grade_pogruzkas');
    }
};
