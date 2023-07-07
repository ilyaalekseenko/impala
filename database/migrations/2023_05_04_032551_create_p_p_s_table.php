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
        Schema::create('p_p_s', function (Blueprint $table) {
            $table->id();
            $table->text('marka')->nullable();
            $table->text('nomer')->nullable();
            $table->text('dlina')->nullable();
            $table->text('tonn')->nullable();
            $table->text('nomer_documenta')->nullable();
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
        Schema::dropIfExists('p_p_s');
    }
};
