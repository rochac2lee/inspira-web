<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicaAvaliacaoInstituicaoEscola extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indica_avaliacao_instituicao_escola', function (Blueprint $table) {
            $table->unsignedBigInteger('indica_avaliacao_id');
            $table->unsignedInteger('instituicao_id');
            $table->unsignedInteger('escola_id');
            $table->primary(['indica_avaliacao_id','instituicao_id','escola_id'],'pk_indica_ava_inst_esco');
            $table->foreign('indica_avaliacao_id')->references('id')->on('indica_avaliacao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indica_avaliacao_instituicao_escola');
    }
}
