<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAvaliacaoTurma extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_avaliacao_turma', function (Blueprint $table) {
            $table->unsignedBigInteger('avaliacao_id');
            $table->unsignedBigInteger('turma_id');
            $table->primary(['avaliacao_id','turma_id']);
            $table->foreign('avaliacao_id')->references('id')->on('fr_avaliacao');
            $table->foreign('turma_id')->references('id')->on('fr_turmas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_avaliacao_turma');
    }
}
