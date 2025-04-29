<?php

namespace App\Models\Indica;

use App\Models\Escola;
use App\Models\Instituicao;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AvaliacaoTempo extends Model
{
    protected $table = 'indica_avaliacao_tempo';

    protected $fillable = [
        'user_id',
        'indica_avaliacao_id',
        'tempo_na_avaliacao',
        'tempo_fora_avaliacao',
    ];


}
