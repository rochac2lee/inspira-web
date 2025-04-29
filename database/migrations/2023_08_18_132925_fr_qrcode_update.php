<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FrQrcodeUpdate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fr_qrcode', function (Blueprint $table) {
            $table->text('tipo_midia')->nullable();
            $table->text('observacao')->nullable();
            $table->unsignedInteger('disciplina_id');
            $table->unsignedInteger('colecao_livro_id');
            $table->unsignedInteger('ciclo_etapa_id');
            $table->unsignedInteger('user_id');
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
