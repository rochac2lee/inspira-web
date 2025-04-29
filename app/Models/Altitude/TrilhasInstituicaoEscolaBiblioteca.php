<?php

namespace App\Models\Altitude;

use Illuminate\Database\Eloquent\Model;

class TrilhasInstituicaoEscolaBiblioteca extends Model
{
    protected $table = 'trilhas_instituicao_escola_biblioteca';

    protected $fillable = [
        'trilha_id',
        'instituicao_id',
        'escola_id',
        'publico',
        'privado',
    ];
    public $timestamps = false;
}
