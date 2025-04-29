<?php

namespace App\Models\Altitude;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CursoAulaUsuarioEntregavel extends Model
{
    use HasFactory;

    protected $table = 'curso_aula_usuario_entregavel';

    protected $fillable = [
        'user_id',
        'trilha_id',
        'curso_id',
        'aula_id',
        'conteudo_id',
        'entregavel',
        'nome_arquivo',
    ];
}
