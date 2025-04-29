<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaNoticiasTurmas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_noticias_turmas', function (Blueprint $table) {
            $table->unsignedBigInteger('turma_id');
            $table->unsignedBigInteger('agenda_noticia_id');
            $table->unsignedInteger('instituicao_id');
            $table->unsignedInteger('escola_id');
            $table->primary(['instituicao_id','escola_id','turma_id','agenda_noticia_id'], 'pk_agenda_noticias_turmas');
            $table->foreign('agenda_noticia_id', 'fk_agenda_noti_turmas_noti')->references('id')->on('fr_agenda_noticias');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_noticias_turmas');
    }
}
