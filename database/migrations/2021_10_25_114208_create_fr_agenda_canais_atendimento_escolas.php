<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaCanaisAtendimentoEscolas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_canais_atendimento_escolas', function (Blueprint $table) {
            $table->unsignedBigInteger('canal_id');
            $table->unsignedInteger('instituicao_id');
            $table->unsignedInteger('escola_id');
            $table->primary(['instituicao_id','escola_id','canal_id'], 'pk_agenda_canal_escola');
            $table->foreign('canal_id', 'fk_agenda_canal_escola_canal')->references('id')->on('fr_agenda_canais_atendimentos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_canais_atendimento_escolas');
    }
}
