<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicaAvaliacaoTentativas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indica_avaliacao_tentativas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('indica_avaliacao_id');
            $table->unsignedInteger('user_id');
            $table->unsignedBigInteger('instituicao_id')->nullable();
            $table->unsignedBigInteger('escola_id')->nullable();
            $table->char('iniciou',1);
            $table->timestamps();
            $table->foreign('indica_avaliacao_id')->references('id')->on('indica_avaliacao');
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
        Schema::dropIfExists('indica_avaliacao_tentativas');
    }
}
