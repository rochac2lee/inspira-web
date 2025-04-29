<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIndicaAvaliacaoLogAtividade20221230 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('indica_avaliacao_log_atividade', function (Blueprint $table) {
            $table->unsignedBigInteger('instituicao_id')->after('corrigida')->nullable();
            $table->unsignedBigInteger('escola_id')->after('instituicao_id')->nullable();
            $table->string('turma_id',300)->after('escola_id')->nullable();
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
