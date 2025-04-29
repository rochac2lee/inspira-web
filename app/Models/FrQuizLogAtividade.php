<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrQuizLogAtividade
 *
 * @property int $id
 * @property int $user_id
 * @property int $quiz_id
 * @property int $pergunta_id
 * @property string $resposta
 * @property string|null $feedback
 * @property string $eh_correta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $tentativa
 * @property int $tempo
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizLogAtividade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizLogAtividade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizLogAtividade query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizLogAtividade whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizLogAtividade whereEhCorreta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizLogAtividade whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizLogAtividade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizLogAtividade wherePerguntaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizLogAtividade whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizLogAtividade whereResposta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizLogAtividade whereTempo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizLogAtividade whereTentativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizLogAtividade whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizLogAtividade whereUserId($value)
 * @mixin \Eloquent
 */
class FrQuizLogAtividade extends Model
{

    protected $table = 'fr_quiz_log_atividade';

    protected $fillable = [
        'user_id',
        'quiz_id',
        'pergunta_id',
        'resposta',
        'feedback',
        'eh_correta',
        'tentativa',
        'tempo',
    ];

}
