<?php

namespace App\Models\Altitude;

use Illuminate\Database\Eloquent\Model;

class CursosInstituicaoEscolaBiblioteca extends Model
{
    protected $table = 'cursos_instituicao_escola_biblioteca';

    protected $fillable = [
        'curso_id',
        'instituicao_id',
        'escola_id',
        'publico',
        'privado',
    ];
    public $timestamps = false;
}
