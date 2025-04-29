<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicaAvaliacaoLogGeral extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indica_avaliacao_log_geral', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('indica_avaliacao_id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('indica_questao_id');
            $table->mediumText('resposta');
            $table->integer('tempo_ativo')->default(0);
            $table->integer('tempo_inativo')->default(0);
            $table->char('finalizado')->default(0);
            $table->mediumText('ordem_questao');
            $table->timestamps();
            $table->foreign('indica_avaliacao_id')->references('id')->on('indica_avaliacao');
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
        Schema::dropIfExists('indica_avaliacao_log_geral');
    }
}
