<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColecaoDocumentoInstituicaoPermissao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colecao_documento_instituicao_permissao', function (Blueprint $table) {
            $table->unsignedInteger('colecao_id');
            $table->unsignedInteger('instituicao_id');
            $table->unsignedInteger('cicloetapa_id');
            $table->foreign(['colecao_id','instituicao_id'], 'fk_permissao_colecao_doc_inst')->references(['colecao_id','instituicao_id'])->on('colecao_prova_instituicao');
            $table->foreign('cicloetapa_id', 'fk_permissao_colecao_doc_inst_ciclo_etapa')->references('id')->on('ciclo_etapas');
            $table->primary(['colecao_id','instituicao_id','cicloetapa_id'],'pk_permissao_doc_escola');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colecao_documento_instituicao_permissao');
    }
}
