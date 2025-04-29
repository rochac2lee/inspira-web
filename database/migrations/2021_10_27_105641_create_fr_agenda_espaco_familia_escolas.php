<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaEspacoFamiliaEscolas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_espaco_familia_escolas', function (Blueprint $table) {
            $table->unsignedBigInteger('familia_id');
            $table->unsignedInteger('instituicao_id')->nullable();
            $table->unsignedInteger('escola_id')->nullable();
            $table->string('publico',1)->default('0');
            $table->string('privado',1)->default('0');
            $table->primary(['instituicao_id','escola_id','familia_id'], 'pk_agenda_fami_escolas');
            $table->foreign('familia_id', 'fk_agenda_fami_escola_fami')->references('id')->on('fr_agenda_espaco_familia');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_espaco_familia_escolas');
    }
}
