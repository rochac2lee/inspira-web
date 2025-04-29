<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAvaliacaoPlacar
 *
 * @property int $id
 * @property int $avaliacao_id
 * @property int $user_id
 * @property string $porcentagem_acerto
 * @property string $porcentagem_erro
 * @property string $porcentagem_acerto_peso
 * @property string $porcentagem_erro_peso
 * @property int $qtd_em_branco
 * @property int $qtd_acerto
 * @property int $qtd_erro
 * @property string $peso_total
 * @property string $peso_total_acerto
 * @property string $peso_total_erro
 * @property int $qtd_questao_para_avaliar
 * @property int $qtd_questoes_total
 * @property int $qtd_questoes_respondida
 * @property string $tempo_ativo
 * @property string $tempo_inativo
 * @property string $tempo_total
 * @property string $questoes
 * @property string $time_line
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereAvaliacaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar wherePesoTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar wherePesoTotalAcerto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar wherePesoTotalErro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar wherePorcentagemAcerto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar wherePorcentagemAcertoPeso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar wherePorcentagemErro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar wherePorcentagemErroPeso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereQtdAcerto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereQtdEmBranco($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereQtdErro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereQtdQuestaoParaAvaliar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereQtdQuestoesRespondida($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereQtdQuestoesTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereQuestoes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereTempoAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereTempoInativo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereTempoTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereTimeLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoPlacar whereUserId($value)
 * @mixin \Eloquent
 */
class FrAvaliacaoPlacar extends Model
{
    protected $table = 'fr_avaliacao_placar';

    protected $fillable = [
        'user_id',
        'avaliacao_id',
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
        'time_line',
    ];
}
