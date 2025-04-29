<?php

namespace App\Models\Ead;

use Illuminate\Database\Eloquent\Model;

class AvaliacaoPlacarHistorico extends Model
{
    protected $table = 'ead_avaliacao_placar_historico';

    protected $fillable = [
        'user_id',
        'ead_avaliacao_id',
        'porcentagem_acerto',
        'porcentagem_erro',
        'porcentagem_acerto_peso',
        'porcentagem_erro_peso',
        'qtd_em_branco',
        'qtd_acerto',
        'qtd_erro',
        'peso_total',
        'peso_total_acerto',
        'peso_total_erro',
        'qtd_questao_para_avaliar',
        'pontuacao',
        'qtd_questoes_total',
        'qtd_questoes_respondida',
        'tempo_ativo',
        'tempo_inativo',
        'tempo_total',
        'questoes',
    ];
}
