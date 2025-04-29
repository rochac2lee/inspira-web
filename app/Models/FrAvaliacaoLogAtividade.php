<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAvaliacaoLogAtividade
 *
 * @property int $id
 * @property int $user_id
 * @property int $avaliacao_id
 * @property int $questao_id
 * @property string|null $resposta
 * @property int $tempo_ativo
 * @property int $tempo_inativo
 * @property string $corrigida
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogAtividade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogAtividade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogAtividade query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogAtividade whereAvaliacaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogAtividade whereCorrigida($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogAtividade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogAtividade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogAtividade whereQuestaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogAtividade whereResposta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogAtividade whereTempoAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogAtividade whereTempoInativo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogAtividade whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoLogAtividade whereUserId($value)
 * @mixin \Eloquent
 */
class FrAvaliacaoLogAtividade extends Model
{
    protected $table = 'fr_avaliacao_log_atividade';

    protected $fillable = [
        'user_id',
        'avaliacao_id',
        'questao_id',
        'tempo_ativo',
        'tempo_inativo',
        'resposta',
    ];
}
