<?php

namespace App\Models\Indica;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImportaQuestao extends Model
{
    use SoftDeletes;
    protected $table = 'indica_importa_questao';

    protected $fillable = [
        'user_id',
        'caminho',
        'nome_arquivo',
        'qtd_linhas',
        'qtd_linhas_certas',
        'qtd_linhas_erros',
        'finalizado',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
