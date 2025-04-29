<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaEspacoFamilia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_espaco_familia', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('titulo', 255);
            $table->mediumText('descricao');
            $table->string('link_video', 255)->nullable();
            $table->string('publicado', 1)->default(0);
            $table->string('permissao_usuario', 1);
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('fr_agenda_espaco_familia');
    }
}
