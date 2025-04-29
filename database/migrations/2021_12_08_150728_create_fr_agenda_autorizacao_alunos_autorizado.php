<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaAutorizacaoAlunosAutorizado extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_autorizacao_alunos_autorizado', function (Blueprint $table) {
            $table->unsignedBigInteger('turma_id');
            $table->unsignedInteger('aluno_id');
            $table->unsignedBigInteger('autorizacao_id');
            $table->unsignedInteger('responsavel_id');
            $table->char('autorizado',1);
            $table->timestamps();
            $table->primary(['turma_id', 'aluno_id', 'autorizacao_id'], 'pk_age_aut_alu_aut');
            $table->foreign(['turma_id', 'aluno_id', 'autorizacao_id'], 'fk_agenda_autorizado')->references(['turma_id', 'aluno_id', 'autorizacao_id' ])->on('fr_agenda_autorizacao_alunos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_autorizacao_alunos_autorizado');
    }
}
