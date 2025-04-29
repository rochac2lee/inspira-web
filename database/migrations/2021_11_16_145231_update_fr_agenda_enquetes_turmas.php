<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFrAgendaEnquetesTurmas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fr_agenda_enquetes_turmas', function (Blueprint $table) {
            $table->unsignedInteger('aluno_id')->after('turma_id');
            $table->foreign(['turma_id', 'aluno_id'], 'fk_agenda_enquete_turmas')->references(['turma_id', 'aluno_id'])->on('fr_turma_aluno');
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
