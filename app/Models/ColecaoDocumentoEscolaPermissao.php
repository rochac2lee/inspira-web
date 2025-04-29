<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ColecaoDocumentoEscolaPermissao extends Model
{
    protected $table = 'colecao_documento_escola_permissao';

    //Preenchiveis
    protected $fillable = [
        'colecao_id',
        'escola_id',
        'cicloetapa_id',
        'todos',
    ];

}
