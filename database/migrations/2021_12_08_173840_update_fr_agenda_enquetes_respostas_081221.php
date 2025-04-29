<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFrAgendaEnquetesRespostas081221 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fr_agenda_enquetes_turmas', function (Blueprint $table) {
            $table->dropPrimary();
            $table->primary(['turma_id','aluno_id','enquete_id'], 'pk_agenda_enquetes_turmas');

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
