<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaAutorizacaoAlunos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_autorizacao_alunos', function (Blueprint $table) {
            $table->unsignedBigInteger('turma_id');
            $table->unsignedInteger('aluno_id');
            $table->unsignedBigInteger('autorizacao_id');
            $table->unsignedInteger('instituicao_id');
            $table->integer('escola_id');
            $table->primary(['turma_id','aluno_id','autorizacao_id'], 'pk_agenda_autorizacao_alunos');
            $table->foreign(['turma_id', 'aluno_id'], 'fk_agenda_autorizacao_turmas')->references(['turma_id', 'aluno_id'])->on('fr_turma_aluno');
            $table->foreign('autorizacao_id', 'fk_agenda_autorizacao_turmas_autorizacao')->references('id')->on('fr_agenda_autorizacao');
            $table->foreign('instituicao_id', 'fk_agenda_aut_inst')->references('id')->on('instituicao');
            $table->foreign('escola_id', 'fk_agenda_aut_esc')->references('id')->on('escolas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_autorizacao_alunos');
    }
}
