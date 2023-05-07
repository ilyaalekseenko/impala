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
        Schema::create('voditels', function (Blueprint $table) {
            $table->id();
            $table->text('FIO')->nullable();
            $table->text('telefon')->nullable();
            $table->text('dataRozdeniya')->nullable();
            $table->text('seriyaPassporta')->nullable();
            $table->text('kemVydan')->nullable();
            $table->text('kogdaVydan')->nullable();
            $table->text('propiska')->nullable();
            $table->text('kompaniya')->nullable();
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
        Schema::dropIfExists('voditels');
    }
};
