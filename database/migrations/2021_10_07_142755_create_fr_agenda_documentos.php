<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_documentos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->mediumText('descricao');
            $table->string('arquivo', 255)->nullable();
            $table->string('nome_arquivo_original', 255)->nullable();
            $table->string('permissao', 2);
            $table->unsignedInteger('instituicao_id');
            $table->integer('escola_id');
            $table->string('publicado', 1)->default(0);
            $table->timestamps();
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
        Schema::dropIfExists('fr_agenda_documentos');
    }
}
