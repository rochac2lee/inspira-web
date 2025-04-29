<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColecaoDocumentoInstituicao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colecao_documento_instituicao', function (Blueprint $table) {
            $table->unsignedInteger('colecao_id');
            $table->unsignedInteger('instituicao_id');
            $table->char('todos',1);
            $table->foreign('colecao_id','fk_colecao_doc_inst_colecao')->references('id')->on('colecao_livros');
            $table->foreign('instituicao_id', 'fk_colecao_doc_inst_inst')->references('id')->on('instituicao');
            $table->primary(['colecao_id','instituicao_id']);
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
        Schema::dropIfExists('colecao_documento_instituicao');
    }
}
