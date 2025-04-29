<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrResponsavelEstudante extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_responsavel_aluno', function (Blueprint $table) {
            $table->unsignedInteger('responsavel_id');
            $table->unsignedInteger('aluno_id');
            $table->unsignedInteger('instituicao_id');
            $table->integer('escola_id');

            $table->primary(['responsavel_id','aluno_id','instituicao_id','escola_id'], 'pk_responsavel_estudante');
            $table->foreign('responsavel_id')->references('id')->on('users');
            $table->foreign('aluno_id')->references('id')->on('users');
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
        Schema::dropIfExists('fr_responsavel_estudante');
    }
}
