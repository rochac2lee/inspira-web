<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIndicaAvaliacaoPlacar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indica_avaliacao_placar', function (Blueprint $table) {
            $table->integer('qtd_tentativas')->default(0)->after('matricula');
            $table->string('tempo_janela_fechada',50)->default(0)->after('qtd_tentativas');
            $table->string('tempo_janela_aberta',50)->default(0)->after('tempo_janela_fechada');
            $table->string('tempo_total_tentativas',50)->default(0)->after('tempo_janela_aberta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
