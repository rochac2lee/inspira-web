<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFrAgendaCanaisAtendimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fr_agenda_canais_atendimentos', function (Blueprint $table) {
            $table->dropForeign('fr_agenda_canais_atendimentos_escola_id_foreign');
            $table->dropColumn('escola_id');
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
