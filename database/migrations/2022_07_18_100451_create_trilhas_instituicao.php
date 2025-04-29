<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrilhasInstituicao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trilhas_instituicao_biblioteca', function (Blueprint $table) {
            $table->unsignedInteger('trilha_id');
            $table->unsignedInteger('instituicao_id');
            $table->foreign('instituicao_id', 'fk_trilha_instituicao_inst')->references('id')->on('instituicao');
            $table->foreign('trilha_id', 'fk_trilha_instituicao_trilha')->references('id')->on('trilhas');
            $table->primary(['trilha_id','instituicao_id'],'pk_trilha_inst');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trilhas_instituicao');
    }
}
