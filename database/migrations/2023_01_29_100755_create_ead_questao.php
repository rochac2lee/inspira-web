<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEadQuestao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ead_questao', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('disciplina_id');
            $table->string('dificuldade',100);
            $table->char('tipo',1);
            $table->mediumText('pergunta');
            $table->mediumText('alternativa_1')->nullable();
            $table->mediumText('alternativa_2')->nullable();
            $table->mediumText('alternativa_3')->nullable();
            $table->mediumText('alternativa_4')->nullable();
            $table->mediumText('alternativa_5')->nullable();
            $table->mediumText('alternativa_6')->nullable();
            $table->mediumText('alternativa_7')->nullable();
            $table->integer('qtd_alternativa')->nullable();
            $table->integer('correta')->nullable();
            $table->char('com_linhas',1)->default(0);
            $table->integer('qtd_linhas')->nullable();
            $table->mediumText('resolucao')->nullable();
            $table->string('link_video_resolucao',2048)->nullable();
            $table->unsignedInteger('bncc_id')->nullable();
            $table->unsignedInteger('cicloetapa_id')->nullable();
            $table->string('unidade_tematica',255)->nullable();
            $table->unsignedInteger('suporte_id')->nullable();
            $table->unsignedInteger('formato_id')->nullable();
            $table->text('palavras_chave')->nullable();
            $table->text('fonte')->nullable();
            $table->unsignedInteger('tema_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('bncc_id')->references('id')->on('fr_bncc');
            $table->foreign('cicloetapa_id')->references('id')->on('ciclo_etapas');
            $table->foreign('disciplina_id')->references('id')->on('disciplinas');
            $table->foreign('formato_id')->references('id')->on('fr_questao_formato');
            $table->foreign('suporte_id')->references('id')->on('fr_questao_suporte');
            $table->foreign('tema_id')->references('id')->on('fr_questao_tema');
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
        Schema::dropIfExists('ead_questao');
    }
}
