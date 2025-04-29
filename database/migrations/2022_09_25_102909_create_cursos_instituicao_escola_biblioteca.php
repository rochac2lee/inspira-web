<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCursosInstituicaoEscolaBiblioteca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cursos_instituicao_escola_biblioteca', function (Blueprint $table) {
            $table->integer('curso_id');
            $table->unsignedInteger('instituicao_id');
            $table->unsignedInteger('escola_id');
            $table->char('publico',1)->default('0');
            $table->char('privado',1)->default('0');
            $table->foreign('curso_id', 'fk_curso_inst_esc_bibli_curso')->references('id')->on('cursos');
            $table->primary(['curso_id', 'instituicao_id', 'escola_id'],'pk_curso_inst_esc_bibli');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cursos_instituicao_escola_biblioteca');
    }
}
