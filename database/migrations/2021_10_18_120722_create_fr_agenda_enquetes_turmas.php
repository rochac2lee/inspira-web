<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaEnquetesTurmas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_enquetes_turmas', function (Blueprint $table) {
            $table->unsignedBigInteger('turma_id');
            $table->unsignedBigInteger('enquete_id');
            $table->unsignedInteger('instituicao_id');
            $table->unsignedInteger('escola_id');
            $table->primary(['instituicao_id','escola_id','turma_id','enquete_id'], 'pk_agenda_enquetes_turmas');
            $table->foreign('enquete_id', 'fk_agenda_enque_turmas_enque')->references('id')->on('fr_agenda_enquetes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_enquetes_turmas');
    }
}
