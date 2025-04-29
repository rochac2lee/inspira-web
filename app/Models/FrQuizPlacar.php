<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrQuizPlacar
 *
 * @property int $id
 * @property int $quiz_id
 * @property int $user_id
 * @property string $porcentagem_acerto
 * @property string $porcentagem_erro
 * @property int $qtd_acerto
 * @property int $qtd_erro
 * @property int $pontuacao
 * @property int $qtd_questoes_total
 * @property int $qtd_questoes_respondida
 * @property string $tempo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $quiz_respondido
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar wherePontuacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar wherePorcentagemAcerto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar wherePorcentagemErro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar whereQtdAcerto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar whereQtdErro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar whereQtdQuestoesRespondida($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar whereQtdQuestoesTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar whereQuizRespondido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar whereTempo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPlacar whereUserId($value)
 * @mixin \Eloquent
 */
class FrQuizPlacar extends Model
{
    protected $table = 'fr_quiz_placar';

    protected $fillable = [
        'user_id',
        'quiz_id',
        'porcentagem_acerto',
        'porcentagem_erro',
        'qtd_acerto',
        'qtd_erro',
        'pontuacao',
        'qtd_questoes_total',
        'qtd_questoes_respondida',
        'tempo',
        'quiz_respondido',
    ];
}
