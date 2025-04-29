<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaComunicadosFrTurmas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_comunicados_fr_turmas', function (Blueprint $table) {
            $table->unsignedBigInteger('turma_id');
            $table->unsignedBigInteger('agenda_comunicado_id');
            $table->primary(['turma_id','agenda_comunicado_id'], 'pk_agenda_comu_turmas');
            $table->foreign('turma_id', 'agenda_comu_turmas')->references('id')->on('fr_turmas');
            $table->foreign('agenda_comunicado_id', 'fk_agenda_comu_turmas_comu')->references('id')->on('fr_agenda_comunicados');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_comunicados_fr_turmas');
    }
}
