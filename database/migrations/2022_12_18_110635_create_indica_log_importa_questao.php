<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicaLogImportaQuestao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indica_log_importa_questao', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('importacao_id');
            $table->unsignedInteger('linha');
            $table->char('erro',1);
            $table->mediumText('erro_validacao')->nullable();
            $table->mediumText('erro_banco')->nullable();
            $table->mediumText('dados_linha');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('importacao_id', 'fk_indica_importa_log')->references('id')->on('indica_importa_questao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('indica_log_importa_questao');
    }
}
