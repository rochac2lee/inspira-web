<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaTarefaAlunos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_tarefa_alunos', function (Blueprint $table) {
            $table->unsignedBigInteger('turma_id');
            $table->unsignedInteger('aluno_id');
            $table->unsignedBigInteger('tarefa_id');
            $table->unsignedInteger('instituicao_id');
            $table->integer('escola_id');
            $table->string('realizado',1)->default('0');
            $table->primary(['turma_id','aluno_id','tarefa_id'], 'pk_agenda_tarefa_alunos');
            $table->foreign(['turma_id', 'aluno_id'], 'fk_agenda_tarefa_turmas')->references(['turma_id', 'aluno_id'])->on('fr_turma_aluno');
            $table->foreign('tarefa_id', 'fk_agenda_tarefa_turmas_tarefa')->references('id')->on('fr_agenda_tarefa');
            $table->foreign('instituicao_id', 'fk_agenda_noti_inst')->references('id')->on('instituicao');
            $table->foreign('escola_id', 'fk_agenda_noti_esc')->references('id')->on('escolas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_tarefa_alunos');
    }
}
