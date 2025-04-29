<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFrAgendaEnquetesRespostas08122101 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fr_agenda_enquetes_respostas', function (Blueprint $table) {
            $table->foreign(['turma_id', 'aluno_id', 'enquete_id'], 'fk_agenda_enq_resp')->references(['turma_id', 'aluno_id', 'enquete_id' ])->on('fr_agenda_enquetes_turmas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
