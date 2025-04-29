<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaCalendarioEscola extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_calendario_escola', function (Blueprint $table) {
            $table->unsignedBigInteger('calendario_id');
            $table->unsignedInteger('instituicao_id');
            $table->unsignedInteger('escola_id');
            $table->primary(['instituicao_id','escola_id','calendario_id'], 'pk_fr_calendario_escola');
            $table->foreign('calendario_id')->references('id')->on('fr_agenda_calendario');
            $table->foreign('instituicao_id')->references('id')->on('instituicao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_calendario_escola');
    }
}
