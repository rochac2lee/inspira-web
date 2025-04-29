<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFrAgendaEnquetes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fr_agenda_enquetes', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->char('permissao_usuario', 1);
            $table->text('pergunta');
            $table->text('alternativa_1')->nullable();
            $table->text('alternativa_2')->nullable();
            $table->text('alternativa_3')->nullable();
            $table->text('alternativa_4')->nullable();
            $table->text('alternativa_5')->nullable();
            $table->text('alternativa_6')->nullable();
            $table->text('alternativa_7')->nullable();
            $table->integer('qtd_alternativa');
            $table->string('imagem',255);
            $table->string('publicado', 1)->default(0);
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
        Schema::dropIfExists('fr_agenda_enquetes');
    }
}
