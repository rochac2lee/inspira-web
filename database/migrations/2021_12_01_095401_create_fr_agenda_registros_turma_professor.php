<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaRegistrosTurmaProfessor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_registros_turma_professor', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('registro_id');
            $table->unsignedBigInteger('turma_id');
            $table->unsignedInteger('professor_id');
            $table->date('data');
            $table->foreign('registro_id', 'fk_agenda_reg_turm_prof')->references('id')->on('fr_agenda_registros_rotinas');
            $table->foreign('turma_id', 'fk_agenda_reg_turm_prof_turm')->references('id')->on('fr_turmas');
            $table->foreign('professor_id', 'fk_agenda_reg_turm_prof_prof')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_registros_turma_professor');
    }
}
