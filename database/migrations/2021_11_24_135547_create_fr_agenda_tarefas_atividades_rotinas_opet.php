<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaTarefasAtividadesRotinasOpet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_registros_rotinas_opet', function (Blueprint $table) {
            $table->id();
            $table->string('titulo',255);
            $table->string('imagem',255);
            $table->unsignedInteger('ordem')->nullable();
            $table->unsignedInteger('rotina');
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
        Schema::dropIfExists('fr_agenda_tarefas_atividades_rotinas_opet');
    }
}
