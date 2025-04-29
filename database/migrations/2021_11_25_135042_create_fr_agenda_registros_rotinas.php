<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaRegistrosRotinas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_registros_rotinas', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('instituicao_id');
            $table->string('titulo',255);
            $table->string('imagem',255);
            $table->unsignedInteger('ordem')->nullable();
            $table->unsignedInteger('rotina');
            $table->timestamps();
            $table->foreign('instituicao_id', 'fk_agenda_reg_rot_instituicao')->references('id')->on('instituicao');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_registros_rotinas');
    }
}
