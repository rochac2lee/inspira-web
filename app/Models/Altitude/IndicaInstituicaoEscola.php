<?php

namespace App\Models\Altitude;

use Illuminate\Database\Eloquent\Model;

class IndicaInstituicaoEscola extends Model
{
    protected $table = 'indica_avaliacao_instituicao_escola';

    protected $fillable = [
        'indica_avaliacao_id',
        'instituicao_id',
        'escola_id',
    ];
    public $timestamps = false;
}
