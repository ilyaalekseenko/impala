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
        Schema::table('docs_vars', function (Blueprint $table) {
            $table->text('width_cell')->nullable();
            $table->text('var_name')->nullable();
            $table->text('font_size')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('docs_vars', function (Blueprint $table) {
            //
        });
    }
};
