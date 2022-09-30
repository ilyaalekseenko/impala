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
        Schema::create('grade_summas', function (Blueprint $table) {
            $table->id();
            $table->integer('grade_id');
            $table->integer('id_ts');
            $table->integer('id_summa');
            $table->integer('summa')->default(null);;
            $table->string('data')->default(null);;
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
        Schema::dropIfExists('grade_summas');
    }
};
