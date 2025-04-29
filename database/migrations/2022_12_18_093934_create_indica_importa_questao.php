<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicaImportaQuestao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indica_importa_questao', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('caminho', 2048);
            $table->string('nome_arquivo', 255);
            $table->unsignedInteger('qtd_linhas');
            $table->unsignedInteger('qtd_linhas_certas')->default(0);
            $table->unsignedInteger('qtd_linhas_erros')->default(0);
            $table->char('finalizado')->default(0);
            $table->timestamps();
            $table->softDeletes();
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
        Schema::dropIfExists('indica_importa_questao');
    }
}
