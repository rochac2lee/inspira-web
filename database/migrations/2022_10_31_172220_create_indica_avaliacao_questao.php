<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicaAvaliacaoQuestao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indica_avaliacao_questao', function (Blueprint $table) {
            $table->unsignedBigInteger('indica_avaliacao_id');
            $table->unsignedBigInteger('indica_questao_id');

            $table->primary(['indica_avaliacao_id','indica_questao_id'],'pk_indica_ava_quest');
            $table->foreign('indica_avaliacao_id')->references('id')->on('indica_avaliacao');
            $table->foreign('indica_questao_id')->references('id')->on('indica_questao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indica_avaliacao_questao');
    }
}
