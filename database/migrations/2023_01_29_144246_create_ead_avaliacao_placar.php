<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEadAvaliacaoPlacar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ead_avaliacao_placar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ead_avaliacao_id');
            $table->unsignedInteger('user_id');
            $table->decimal('porcentagem_acerto',12,2);
            $table->decimal('porcentagem_erro',12,2);
            $table->decimal('porcentagem_acerto_peso',12,2);
            $table->decimal('porcentagem_erro_peso',12,2);
            $table->integer('qtd_em_branco');
            $table->integer('qtd_acerto');
            $table->integer('qtd_erro');
            $table->decimal('peso_total',12,2);
            $table->decimal('peso_total_acerto',12,2);
            $table->decimal('peso_total_erro',12,2);
            $table->integer('qtd_questao_para_avaliar');
            $table->integer('qtd_questoes_total');
            $table->integer('qtd_questoes_respondida');
            $table->text('tempo_ativo');
            $table->text('tempo_inativo');
            $table->text('tempo_total');
            $table->mediumText('questoes');
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
        Schema::dropIfExists('ead_avaliacao_placar');
    }
}
