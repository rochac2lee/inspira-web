<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFrAgendaComunicadosFrTurmas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fr_agenda_comunicados_fr_turmas', function (Blueprint $table) {
            $table->dropForeign('agenda_comu_turmas');
            $table->dropPrimary('pk_agenda_comu_turmas');
            $table->unsignedInteger('instituicao_id');
            $table->unsignedInteger('escola_id');
            $table->primary(['instituicao_id','escola_id','turma_id','agenda_comunicado_id'], 'pk_agenda_comunicado_turmas');
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
