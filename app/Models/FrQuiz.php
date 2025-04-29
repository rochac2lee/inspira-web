<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrQuiz
 *
 * @property int $id
 * @property string $titulo
 * @property string|null $capa
 * @property int $user_id
 * @property int|null $escola_id
 * @property int|null $instituicao_id
 * @property int $disciplina_id
 * @property int $ciclo_etapa_id
 * @property string $aleatorizar_questoes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string $nivel
 * @property string|null $unidade_tematica
 * @property string|null $habilidades
 * @property int|null $colecao_livro_id
 * @property string $publicado
 * @property string|null $palavras_chave
 * @property int $qtd_tentativas
 * @property int|null $pontuacao
 * @property string|null $full_text
 * @property string|null $public_id
 * @property-read \App\Models\Disciplina|null $disciplina
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrQuizPlacar[] $finalizado
 * @property-read int|null $finalizado_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrQuizPerguntas[] $qtdPerguntas
 * @property-read int|null $qtd_perguntas_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrQuizLogAtividade[] $respondido
 * @property-read int|null $respondido_count
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereAleatorizarQuestoes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereCapa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereCicloEtapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereColecaoLivroId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereDisciplinaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereFullText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereHabilidades($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereNivel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz wherePalavrasChave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz wherePontuacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz wherePublicId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz wherePublicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereQtdTentativas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereUnidadeTematica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuiz whereUserId($value)
 * @mixin \Eloquent
 */
class FrQuiz extends Model
{

    protected $table = 'fr_quiz';

    protected $fillable = [
        'titulo',
        'capa',
        'user_id',
        'escola_id',
        'instituicao_id',
        'disciplina_id',
        'ciclo_etapa_id',
        'aleatorizar_questoes',
        'nivel',
        'unidade_tematica',
        'habilidades',
        'colecao_livro_id',
        'publicado',
        'palavras_chave',
        'qtd_tentativas',
        'pontuacao',
        'full_text',
        'public_id',
    ];
    public function disciplina()
    {
        return $this->hasOne('App\Models\Disciplina','id','disciplina_id');
    }

    public function finalizado()
    {
        return $this->hasMany('App\Models\FrQuizPlacar','quiz_id','id');
    }

    public function respondido()
    {
        return $this->hasMany('App\Models\FrQuizLogAtividade','quiz_id','id');
    }

    public function qtdPerguntas()
    {
        return $this->hasMany(FrQuizPerguntas::class,'quiz_id','id')
                    ->groupBy('quiz_id')
                    ->selectRaw('count(id) as qtd, quiz_id');
    }

}
