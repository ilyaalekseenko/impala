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
            $table->text('forma_id')->nullable();
            $table->text('nazvanie')->nullable();
            $table->text('data_registracii')->nullable();
            $table->text('telefon')->nullable();
            $table->text('INN')->nullable();
            $table->text('OGRN')->nullable();
            $table->text('email')->nullable();
            $table->text('generalnii_direktor')->nullable();
            $table->text('telefon_gen_dir')->nullable();
            $table->text('email_gen_dir')->nullable();
            $table->text('YR_adres')->nullable();
            $table->text('pochtovyi_adres')->nullable();
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
