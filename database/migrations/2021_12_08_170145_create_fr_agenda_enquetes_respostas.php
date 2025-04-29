<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaEnquetesRespostas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_enquetes_respostas', function (Blueprint $table) {
            $table->unsignedBigInteger('turma_id');
            $table->unsignedInteger('aluno_id');
            $table->unsignedBigInteger('enquete_id');
            $table->unsignedInteger('responsavel_id');
            $table->char('resposta',1);
            $table->timestamps();
            $table->primary(['turma_id', 'aluno_id', 'enquete_id'], 'pk_age_enq_resp');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_enquetes_respostas');
    }
}
