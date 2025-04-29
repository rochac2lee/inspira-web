<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEadAvaliacaoLogGeral extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ead_avaliacao_log_geral', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ead_avaliacao_id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('ead_questao_id');
            $table->integer('tempo_ativo')->default(0);
            $table->integer('tempo_inativo')->default(0);
            $table->dateTime('iniciou_ativo')->nullable();
            $table->dateTime('iniciou_inativo')->nullable();
            $table->mediumText('resposta');
            $table->char('finalizado')->default(0);
            $table->mediumText('ordem_questao');
            $table->timestamps();
            $table->foreign('ead_avaliacao_id')->references('id')->on('ead_avaliacao');
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
        Schema::dropIfExists('ead_avaliacao_log_geral');
    }
}
