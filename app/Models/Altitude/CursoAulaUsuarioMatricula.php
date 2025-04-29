<?php

namespace App\Models\Altitude;

use Illuminate\Database\Eloquent\Model;

class CursoAulaUsuarioMatricula extends Model
{
	protected $table = 'curso_aula_usuario_matricula';

    protected $fillable = [
        'user_id',
        'trilha_id',
        'curso_id',
        'aula_id',
        'conteudo_id',
        'qtd',
    ];
}
