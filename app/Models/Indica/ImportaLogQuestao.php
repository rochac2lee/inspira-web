<?php

namespace App\Models\Indica;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImportaLogQuestao extends Model
{
    use SoftDeletes;
    protected $table = 'indica_log_importa_questao';

    protected $fillable = [
        'importacao_id',
        'linha',
        'erro',
        'erro_validacao',
        'erro_banco',
        'dados_linha',
    ];

}
