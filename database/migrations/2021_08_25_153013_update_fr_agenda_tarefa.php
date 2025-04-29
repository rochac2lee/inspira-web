<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFrAgendaTarefa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fr_agenda_tarefa', function (Blueprint $table) {
            $table->string('publicado', 1)->default(0);
            $table->string('nome_arquivo_original', 255)->nullable()->after('arquivo');
            $table->foreign('professor_id')->references('id')->on('users');
            $table->foreign('instituicao_id')->references('id')->on('instituicao');
            $table->foreign('escola_id')->references('id')->on('escolas');
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
