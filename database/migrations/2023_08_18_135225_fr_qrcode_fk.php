<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FrQrcodeFk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('fr_qrcode', function (Blueprint $table) {
            $table->foreign('disciplina_id')->references('id')->on('disciplinas');
            $table->foreign('colecao_livro_id')->references('id')->on('colecao_livros');
            $table->foreign('ciclo_etapa_id')->references('id')->on('ciclo_etapas');
            $table->foreign('user_id')->references('id')->on('users');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fr_qrcode', function (Blueprint $table) {
            $table->dropForeign(['disciplina_id']);
            $table->dropForeign(['colecao_livro_id']);
            $table->dropForeign(['ciclo_etapa_id']);
            $table->dropColumn('disciplina_id');
            $table->dropColumn('colecao_livro_id');
            $table->dropColumn('ciclo_etapa_id');
        });
    }
}
