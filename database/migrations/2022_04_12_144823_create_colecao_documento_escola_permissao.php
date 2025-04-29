<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColecaoDocumentoEscolaPermissao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colecao_documento_escola_permissao', function (Blueprint $table) {
            $table->unsignedInteger('colecao_id');
            $table->integer('escola_id');
            $table->unsignedInteger('cicloetapa_id');
            $table->foreign(['colecao_id','escola_id'])->references(['colecao_id','escola_id'])->on('colecao_audio_escola');
            $table->foreign('cicloetapa_id')->references('id')->on('ciclo_etapas');
            $table->primary(['colecao_id','escola_id','cicloetapa_id'],'pk_permissao_doc_escola');
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
        Schema::dropIfExists('colecao_documento_escola_permissao');
    }
}
