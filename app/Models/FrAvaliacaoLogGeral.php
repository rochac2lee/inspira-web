<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAvaliacaoLogGeral
 *
 * @property int $id
 * @property int $user_id
 * @property int $avaliacao_id
 * @property int $questao_id
 * @property int $tempo_ativo
 * @property int $tempo_inativo
 * @property string|null $iniciou_ativo
 * @property string|null $iniciou_inativo
 * @property string|null $resposta
 * @property string $finalizado
 * @property string|null $ordem_questao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrAvaliacaoPlacar[] $placar
 * @property-read int|null $placar_count
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral whereAvaliacaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral whereFinalizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral whereIniciouAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral whereIniciouInativo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral whereOrdemQuestao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral whereQuestaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral whereResposta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral whereTempoAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral whereTempoInativo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogGeral whereUserId($value)
 * @mixin \Eloquent
 */
class FrAvaliacaoLogGeral extends Model
{
    protected $table = 'fr_avaliacao_log_geral';

    protected $fillable = [
        'user_id',
        'avaliacao_id',
        'questao_id',
        'tempo_ativo',
        'tempo_inativo',
        'iniciou_ativo',
        'iniciou_inativo',
        'resposta',
        'finalizado',
        'ordem_questao',
    ];

    public function placar()
    {
        return $this->hasMany(FrAvaliacaoPlacar::class, 'avaliacao_id');
    }
}
