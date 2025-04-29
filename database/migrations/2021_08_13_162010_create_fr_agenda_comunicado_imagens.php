<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaComunicadoImagens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_comunicado_imagens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comunicado_id');
            $table->string('caminho',255);
            $table->integer('ordem')->default('0');
            $table->foreign('comunicado_id')->references('id')->on('fr_agenda_comunicados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fr_agenda_comunicado_imagens');
    }
}
