<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaTarefa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * perguntar se as tarefas de cada aluno srão avaliadas no sistema da agenda?
         */
        Schema::create('fr_agenda_tarefa', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->mediumText('descricao');
            $table->date('data_entrega');
            $table->unsignedInteger('disciplina_id');
            $table->unsignedInteger('ciclo_etapa_id');
            $table->string('arquivo', 255)->nullable();
            $table->unsignedInteger('professor_id');
            $table->unsignedInteger('instituicao_id');
            $table->integer('escola_id');
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
        Schema::dropIfExists('fr_agenda_tarefa');
    }
}
