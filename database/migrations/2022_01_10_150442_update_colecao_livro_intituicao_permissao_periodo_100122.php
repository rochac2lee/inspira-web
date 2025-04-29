<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColecaoLivroIntituicaoPermissaoPeriodo100122 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('colecao_livro_instituicao_permissao_periodo', function (Blueprint $table) {
            $table->dropForeign('fk_permi_insti_livro_periodo');
            $table->foreign(['colecao_id','instituicao_id'],'fk_permi_insti_livro_periodo')
                ->references(['colecao_id','instituicao_id'])
                ->on('colecao_livro_instituicao')
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
