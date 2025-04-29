<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaCalendario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_calendario', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('titulo',255);
            $table->mediumText('descricao');
            $table->dateTime('data_inicial');
            $table->dateTime('data_final');
            $table->string('dia_inteiro',1);
            $table->string('tema',50)->nullable();
            $table->string('permissao_usuario',1);
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
        Schema::dropIfExists('fr_agenda_calendario');
    }
}
