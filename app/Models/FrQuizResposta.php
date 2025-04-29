<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\FrQuizResposta
 *
 * @property int $id
 * @property string|null $titulo
 * @property string|null $imagem
 * @property string|null $audio
 * @property string $correta
 * @property int $pergunta_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $feedback
 * @property int|null $ordem
 * @property int|null $ordem_correta
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta whereAudio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta whereCorreta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta whereImagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta whereOrdemCorreta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta wherePerguntaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizResposta whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrQuizResposta extends Model
{

    protected $table = 'fr_quiz_resposta';

    protected $fillable = [
        'titulo',
        'imagem',
        'audio',
        'correta',
        'pergunta_id',
        'feedback',
        'ordem',
        'ordem_correta',
    ];
}
