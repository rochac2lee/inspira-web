<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrAgendaFamiliaEscola extends Model
{
    protected $table = 'fr_agenda_espaco_familia_escolas';

    protected $fillable = [
        'instituicao_id',
        'escola_id',
        'familia_id',
    ];
}
