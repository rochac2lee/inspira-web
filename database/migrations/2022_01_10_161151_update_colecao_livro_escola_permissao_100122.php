<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColecaoLivroEscolaPermissao100122 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colecao_livro_escola_permissao', function (Blueprint $table) {
            $table->dropForeign('colecao_livro_escola_permissao_colecao_id_escola_id_foreign');
            $table->foreign(['colecao_id','escola_id'],'colecao_livro_escola_permissao_colecao_id_escola_id_foreign')
                ->references(['colecao_id','escola_id'])
                ->on('colecao_livro_escola')
                ->onUpdate('cascade');
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
