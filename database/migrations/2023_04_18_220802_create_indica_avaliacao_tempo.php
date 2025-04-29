<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicaAvaliacaoTempo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indica_avaliacao_tempo', function (Blueprint $table) {
            $table->unsignedBigInteger('indica_avaliacao_id');
            $table->unsignedInteger('user_id');
            $table->integer('tempo_na_avaliacao')->default(0);
            $table->integer('tempo_fora_avaliacao')->default(0);
            $table->timestamps();
            $table->primary(['indica_avaliacao_id', 'user_id']);
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
        Schema::dropIfExists('indica_avaliacao_tempo');
    }
}
