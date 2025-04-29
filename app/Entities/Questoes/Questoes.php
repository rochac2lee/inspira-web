<?php

namespace App\Entities\Questoes;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Entities\Questoes\Questoes
 *
 * @property int $id
 * @property string|null $alternativas
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $descricao
 * @property string|null $resposta_correta
 * @property int|null $tipo
 * @property string $pergunta
 * @property int $user_id
 * @property int|null $ciclo_id
 * @property int|null $disciplina_id
 * @property int|null $provadificuldade_id
 * @property string|null $tag
 * @property int|null $cicloetapa_id
 * @property string|null $dica
 * @property string|null $explicacao
 * @property int|null $peso
 * @property int|null $textoapoio
 * @property int|null $habilidadebncc_id
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes query()
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereAlternativas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereCicloId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereCicloetapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereDica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereDisciplinaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereExplicacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereHabilidadebnccId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes wherePergunta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes wherePeso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereProvadificuldadeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereRespostaCorreta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereTextoapoio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Questoes whereUserId($value)
 * @mixin \Eloquent
 */
class Questoes extends Model
{
    // tipo == 1 é dissertativa
    // tipo == 2 multipla escolha


    protected $table = "questoes";

    //Preenchiveis
    protected $fillable = [
        'user_id',
        'pergunta',
        'descricao',
        'tipo',
        'alternativas',
        'resposta_correta',
        'peso',
        'dica',
        'explicacao',
        'ciclo_id',
        'disciplina_id',
        'provadificuldade_id',
        'tag',
        'cicloetapa_id',
        'textoapoio',
        'habilidadebncc_id'
    ];

    public function conteudos()
    {
        return $this->belongsToMany('App\Conteudo')->using('App\Entities\Questoes\QuestaoConteudo');
    }

    public function ciclo() {

        return $this->belongsTo('App\Ciclo', 'ciclo_id', 'id');

    }

    public function cicloetapa(){

        return $this->belongsTo('App\CicloEtapa', 'cicloetapa_id', 'id');

    }

    public function dificuldade() {

        return $this->belongsTo('App\Entities\ProvaDificuldade\ProvaDificuldade', 'provadificuldade_id', 'id');

    }

    public function disciplina() {
        return $this->belongsTo('App\Disciplina', 'disciplina_id', 'id');
    }

    public function habilidade() {
        return $this->belongsTo('App\Entities\HabilidadeBNCC\HabilidadeBNCC', 'habilidadebncc_id', 'id');
    }


}
