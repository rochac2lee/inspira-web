<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaNoticiasImagens extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_noticias_imagens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('noticias_id');
            $table->string('caminho',255);
            $table->integer('ordem')->nullable()->default(null);
            $table->foreign('noticias_id')->references('id')->on('fr_agenda_noticias');
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
        Schema::dropIfExists('fr_agenda_noticias_imagens');
    }
}
