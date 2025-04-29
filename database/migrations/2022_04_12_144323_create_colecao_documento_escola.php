<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateColecaoDocumentoEscola extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colecao_documento_escola', function (Blueprint $table) {
            $table->unsignedInteger('colecao_id');
            $table->integer('escola_id');
            $table->char('todos',1);
            $table->foreign('colecao_id')->references('id')->on('colecao_livros');
            $table->foreign('escola_id')->references('id')->on('escolas');
            $table->primary(['colecao_id','escola_id']);
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
        Schema::dropIfExists('colecao_documento_escola');
    }
}
