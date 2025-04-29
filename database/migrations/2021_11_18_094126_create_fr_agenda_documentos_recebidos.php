<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaDocumentosRecebidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_documentos_recebidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('turma_id');
            $table->unsignedInteger('aluno_id');
            $table->unsignedBigInteger('documento_id');
            $table->unsignedInteger('responsavel_id');
            $table->string('arquivo',255);
            $table->timestamps();
            $table->foreign(['turma_id', 'aluno_id', 'documento_id'], 'fk_agenda_documentos_recebidos')->references(['turma_id', 'aluno_id', 'documento_id' ])->on('fr_agenda_documentos_alunos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_documentos_recebidos');
    }
}
