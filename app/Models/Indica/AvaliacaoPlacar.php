<?php

namespace App\Models\Indica;

use App\Models\Escola;
use App\Models\Instituicao;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class AvaliacaoPlacar extends Model
{
    protected $table = 'indica_avaliacao_placar';

    protected $fillable = [
        'user_id',
        'indica_avaliacao_id',
        'porcentagem_acerto',
        'porcentagem_erro',
        'qtd_em_branco',
        'qtd_acerto',
        'qtd_erro',
        'peso_total',
        'qtd_questao_para_avaliar',
        'qtd_questoes_total',
        'qtd_questoes_respondida',
        'tempo_ativo',
        'tempo_inativo',
        'tempo_total',
        'questoes',
        'instituicao_id',
        'escola_id',
        'turma_id',
        'matricula',
        'qtd_tentativas',
        'tempo_janela_fechada',
        'tempo_janela_aberta',
        'tempo_total_tentativas',
        'token',
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
}
