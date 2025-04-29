<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFrAgendaRegistrosTurmaProfessorAluno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fr_agenda_registros_turma_professor_aluno', function (Blueprint $table) {
            $table->foreign('aluno_id', 'fk_agenda_reg_turm_prof_aluno')->references('id')->on('users');
            $table->foreign('registro_turma_id', 'fk_agenda_reg_turm_prof_reg')->references('id')->on('fr_agenda_registros_turma_professor');
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
