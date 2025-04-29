<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAvaliacaoQuestao
 *
 * @property int $avaliacao_id
 * @property int $questao_id
 * @property string|null $peso
 * @property int $ordem
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoQuestao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoQuestao newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoQuestao query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoQuestao whereAvaliacaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoQuestao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoQuestao whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoQuestao wherePeso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoQuestao whereQuestaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacaoQuestao whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrAvaliacaoQuestao extends Model
{
    protected $table = 'fr_avaliacao_questao';

    protected $fillable = [
        'avaliacao_id',
        'questao_id',
        'ordem',
    ];


}
