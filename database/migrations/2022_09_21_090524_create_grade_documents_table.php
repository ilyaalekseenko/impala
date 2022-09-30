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
        Schema::create('grade_documents', function (Blueprint $table) {
            $table->id();
            $table->integer('grade_id');
            $table->integer('id_ts')->nullable();
            $table->integer('id_pogruzka')->nullable();
            $table->integer('id_doc_type')->nullable();
            $table->text('name_doc')->nullable();
            $table->text('path_doc')->nullable();
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
        Schema::dropIfExists('grade_documents');
    }
};
