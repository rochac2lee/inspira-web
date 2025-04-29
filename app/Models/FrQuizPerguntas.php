<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * App\Models\FrQuizPerguntas
 *
 * @property int $id
 * @property string $titulo
 * @property string|null $sub_titulo
 * @property string|null $imagem
 * @property string|null $audio_titulo
 * @property string|null $audio_sub_titulo
 * @property string $tipo
 * @property int $ordem
 * @property int $quiz_id
 * @property string|null $aleatorizar_respostas
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $frase_correta
 * @property string|null $feedback
 * @property string|null $feedback_correta
 * @property int|null $importado_questao
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrQuizResposta[] $respostas
 * @property-read int|null $respostas_count
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereAleatorizarRespostas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereAudioSubTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereAudioTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereFeedback($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereFeedbackCorreta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereFraseCorreta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereImagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereImportadoQuestao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereQuizId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereSubTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuizPerguntas whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrQuizPerguntas extends Model
{

    protected $table = 'fr_quiz_pergunta';

    protected $fillable = [
        'titulo',
        'sub_titulo',
        'imagem',
        'audio_titulo',
        'audio_sub_titulo',
        'tipo',
        'ordem',
        'quiz_id',
        'aleatorizar_respostas',
        'frase_correta',
        'feedback',
        'feedback_correta',
        'importado_questao',

    ];

    public function respostas()
    {
        return $this->hasMany('App\Models\FrQuizResposta','pergunta_id')->orderBy('ordem')->orderBy('id');
    }

    public function log()
    {
        return $this->hasOne('App\Models\FrQuizLogAtividade','pergunta_id')->where('user_id',Auth::user()->id);
    }
}
