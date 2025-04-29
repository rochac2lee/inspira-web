<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColecaoLivroEscolaPermissaoPeriodo1001221 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colecao_livro_escola_permissao_periodo', function (Blueprint $table) {
            $table->dropForeign('fk_permi_escola_livro_periodo');
            $table->foreign(['colecao_id','escola_id'],'fk_permi_escola_livro_periodo')
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
