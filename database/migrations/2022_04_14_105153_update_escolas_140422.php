<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEscolas140422 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('escolas', function (Blueprint $table) {
            $table->string('cep',255)->nullable()->change();
            $table->string('uf',255)->nullable()->change();
            $table->string('cidade',255)->nullable()->change();
            $table->string('bairro',255)->nullable()->change();
            $table->string('logradouro',255)->nullable()->change();
            $table->string('numero',255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
