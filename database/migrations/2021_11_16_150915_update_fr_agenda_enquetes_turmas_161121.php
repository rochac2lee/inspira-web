<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFrAgendaEnquetesTurmas161121 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fr_agenda_enquetes_turmas', function (Blueprint $table) {
            $table->dropPrimary('pk_agenda_enquetes_turmas');
            $table->primary(['instituicao_id','escola_id','turma_id','aluno_id','enquete_id'], 'pk_agenda_enquetes_turmas');
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
