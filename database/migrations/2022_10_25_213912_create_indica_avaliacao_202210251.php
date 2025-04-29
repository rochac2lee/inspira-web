<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndicaAvaliacao202210251 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indica_avaliacao', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->string('titulo',255);
            $table->dateTime('data_hora_inicial');
            $table->dateTime('data_hora_final');
            $table->integer('disciplina_id');
            $table->char('tipo_ordenacao',1)->nullable();
            $table->integer('tempo_maximo')->nullable();
            $table->char('publicado',1)->default(0);
            $table->longText('perguntas')->nullable();
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
        Schema::dropIfExists('indica_avaliacao_202210251');
    }
}
