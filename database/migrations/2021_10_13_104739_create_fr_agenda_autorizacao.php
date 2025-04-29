<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaAutorizacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_autorizacao', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('titulo', 255);
            $table->mediumText('descricao');
            $table->char('permissao_usuario', 1);
            $table->unsignedInteger('instituicao_id');
            $table->integer('escola_id');
            $table->string('publicado', 1)->default(0);
            $table->timestamps();
            $table->foreign('instituicao_id')->references('id')->on('instituicao');
            $table->foreign('escola_id')->references('id')->on('escolas');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_autorizacao');
    }
}
