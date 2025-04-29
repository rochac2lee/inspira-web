<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEadAvaliacaoQuestao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ead_avaliacao_questao', function (Blueprint $table) {
            $table->unsignedBigInteger('ead_avaliacao_id');
            $table->unsignedBigInteger('ead_questao_id');
            $table->decimal('peso',6,2);
            $table->unsignedInteger('ordem');

            $table->primary(['ead_avaliacao_id','ead_questao_id'],'pk_ead_ava_quest');
            $table->foreign('ead_avaliacao_id','fk_ead_av_quest_av')->references('id')->on('ead_avaliacao');
            $table->foreign('ead_questao_id','fk_ead_av_quest_quest')->references('id')->on('ead_questao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ead_avaliacao_questao');
    }
}
