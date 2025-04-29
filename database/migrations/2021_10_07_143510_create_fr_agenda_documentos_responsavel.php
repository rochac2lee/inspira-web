<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaDocumentosResponsavel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_documentos_alunos', function (Blueprint $table) {
            $table->unsignedBigInteger('turma_id');
            $table->unsignedInteger('aluno_id');
            $table->unsignedBigInteger('documento_id');
            $table->unsignedInteger('instituicao_id');
            $table->integer('escola_id');
            $table->primary(['turma_id','aluno_id','documento_id'], 'pk_agenda_tarefa_alunos');
            $table->foreign(['turma_id', 'aluno_id'], 'fk_agenda_documentos_turmas')->references(['turma_id', 'aluno_id'])->on('fr_turma_aluno');
            $table->foreign('documento_id', 'fk_agenda_documentos_turmas_documento')->references('id')->on('fr_agenda_documentos');
            $table->foreign('instituicao_id', 'fk_agenda_doc_inst')->references('id')->on('instituicao');
            $table->foreign('escola_id', 'fk_agenda_doc_esc')->references('id')->on('escolas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_documentos_responsavel');
    }
}
