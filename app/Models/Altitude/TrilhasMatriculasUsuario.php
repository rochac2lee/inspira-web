<?php

namespace App\Models\Altitude;

use App\Models\Trilha;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class TrilhasMatriculasUsuario extends Model
{
	protected $table = 'trilha_usuario_matricula';

    protected $fillable = [
        'user_id',
        'trilha_id',
        'tentativas_avaliacao',
        'porcentagem_acerto',
        'nota',
        'chave_validacao',
        'created_at',
    ];

    public function usuario(){
        return $this->hasOne(User::class, 'id','user_id');
    }
    public function trilha(){
        return $this->hasOne(Trilha::class, 'id','trilha_id');
    }
}
