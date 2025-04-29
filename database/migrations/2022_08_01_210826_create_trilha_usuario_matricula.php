<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrilhaUsuarioMatricula extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trilha_usuario_matricula', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('trilha_id');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('trilha_id')->references('id')->on('trilhas');
            $table->primary(['trilha_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('trilha_usuario_matricula');
    }
}
