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
        Schema::table('perevozkas', function (Blueprint $table) {
            $table->text('kod_ATI')->nullable();
            $table->text('kommentariy')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('perevozkas', function (Blueprint $table) {
            //
        });
    }
};
