<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCursoTemaUsuarioMatricula extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('curso_aula_usuario_matricula', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('trilha_id');
            $table->integer('curso_id');
            $table->unsignedInteger('aula_id');
            $table->integer('conteudo_id');
            $table->unsignedInteger('qtd');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('trilha_id')->references('id')->on('trilhas');
            $table->foreign('curso_id')->references('id')->on('cursos');
            $table->foreign('aula_id')->references('id')->on('aulas');
            $table->foreign('conteudo_id')->references('id')->on('conteudos');
            $table->primary(['curso_id','user_id', 'aula_id', 'conteudo_id'],'pk_cur_aula_user_matri');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('curso_tema_usuario_matricula');
    }
}
