<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaCanaisAtendimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_canais_atendimentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('instituicao_id');
            $table->integer('escola_id')->nullable();
            $table->string('nome');
            $table->string('cargo');
            $table->string('imagem',255);
            $table->string('email');
            $table->string('telefone');
            $table->string('telefone_eh_zap');
            $table->string('publicado', 1)->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('instituicao_id')->references('id')->on('instituicao');
            $table->foreign('escola_id')->references('id')->on('escolas');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_canais_atendimentos');
    }
}
