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
        Schema::create('orders_perevozchiki', function (Blueprint $table) {
            $table->id();
            $table->integer('orders_id')->nullable();
            $table->integer('perevozchik_id')->nullable();
            $table->integer('stavka_NDS')->nullable();
            $table->integer('stavka_bez_NDS')->nullable();
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
        Schema::dropIfExists('orders_perevozchiki');
    }
};
