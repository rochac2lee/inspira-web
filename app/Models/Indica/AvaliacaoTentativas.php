<?php

namespace App\Models\Indica;

use App\Models\Escola;
use App\Models\Instituicao;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AvaliacaoTentativas extends Model
{
    protected $table = 'indica_avaliacao_tentativas';

    protected $fillable = [
        'user_id',
        'indica_avaliacao_id',
        'instituicao_id',
        'escola_id',
        'iniciou',
    ];

    public function usuario()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function instituicao(){
        return $this->hasOne(Instituicao::class,'id','instituicao_id');
    }

    public function escola(){
        return $this->hasOne(Escola::class,'id','escola_id');
    }

    public function placar(){
        return $this->hasMany(AvaliacaoPlacar::class,'indica_avaliacao_id','indica_avaliacao_id');
    }
}
