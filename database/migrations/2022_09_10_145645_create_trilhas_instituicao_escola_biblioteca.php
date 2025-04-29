<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrilhasInstituicaoEscolaBiblioteca extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trilhas_instituicao_escola_biblioteca', function (Blueprint $table) {
            $table->unsignedInteger('trilha_id');
            $table->unsignedInteger('instituicao_id');
            $table->unsignedInteger('escola_id');
            $table->char('publico',1)->default('0');
            $table->char('privado',1)->default('0');
            $table->foreign('trilha_id', 'fk_trilha_inst_esc_bibli_trilha')->references('id')->on('trilhas');
            $table->primary(['trilha_id', 'instituicao_id', 'escola_id'],'pk_trilha_inst_esc_bibli');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trilhas_instituicao_escola_biblioteca');
    }
}
