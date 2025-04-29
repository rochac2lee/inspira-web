<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCursoAulaUsuarioEntregavel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curso_aula_usuario_entregavel', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('trilha_id');
            $table->integer('curso_id');
            $table->unsignedInteger('aula_id');
            $table->integer('conteudo_id');
            $table->string('entregavel', 255);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('trilha_id')->references('id')->on('trilhas');
            $table->foreign('curso_id')->references('id')->on('cursos');
            $table->foreign('aula_id')->references('id')->on('aulas');
            $table->foreign('conteudo_id')->references('id')->on('conteudos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curso_aula_usuario_entregavel');
    }
}
