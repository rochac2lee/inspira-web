<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEadAvaliacao202301301 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ead_avaliacao', function (Blueprint $table) {
            $table->unsignedInteger('tipo_peso')->after('quantidade_minima_questoes');
            $table->unsignedInteger('peso')->after('tipo_peso');
            $table->softDeletes();
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
