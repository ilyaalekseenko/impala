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
        Schema::create('t_s_modals', function (Blueprint $table) {
            $table->id();
            $table->text('tip')->nullable();
            $table->text('marka')->nullable();
            $table->text('nomer')->nullable();
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
        Schema::dropIfExists('t_s_models');
    }
};
