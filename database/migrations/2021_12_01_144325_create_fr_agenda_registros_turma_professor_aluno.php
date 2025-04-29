<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaRegistrosTurmaProfessorAluno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_registros_turma_professor_aluno', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('aluno_id');
            $table->unsignedBigInteger('registro_turma_id');
            $table->char('marcado',1)->nullable();
            $table->string('texto',255)->nullable();
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
        Schema::dropIfExists('fr_agenda_registros_turma_professor_aluno');
    }
}
