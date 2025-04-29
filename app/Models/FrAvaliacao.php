<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAvaliacao
 *
 * @property int $id
 * @property int $instituicao_id
 * @property int $escola_id
 * @property int $user_id
 * @property string $titulo
 * @property \Illuminate\Support\Carbon $data_hora_inicial
 * @property \Illuminate\Support\Carbon $data_hora_final
 * @property int $disciplina_id
 * @property string|null $tipo_peso
 * @property string|null $ordenacao
 * @property string $aplicacao
 * @property string $tipo
 * @property int|null $peso
 * @property string $questoes_final
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $tempo_maximo
 * @property \Illuminate\Support\Carbon|null $data_hora_liberacao_resultado
 * @property int|null $qtd_questao
 * @property string $publicado
 * @property string|null $perguntas
 * @property-read \App\Models\Disciplina $disciplina
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrAvaliacaoLogAtividade[] $logAtividade
 * @property-read int|null $log_atividade_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrAvaliacaoLogGeral[] $logGeral
 * @property-read int|null $log_geral_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrAvaliacaoPlacar[] $placar
 * @property-read int|null $placar_count
 * @property-read int|null $questao_count
 * @property-read \App\Models\User $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereAplicacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereDataHoraFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereDataHoraInicial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereDataHoraLiberacaoResultado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereDisciplinaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereOrdenacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao wherePerguntas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao wherePeso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao wherePublicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereQtdQuestao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereQuestoesFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereTempoMaximo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereTipoPeso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAvaliacao whereUserId($value)
 * @mixin \Eloquent
 */
class FrAvaliacao extends Model
{
    protected $table = 'fr_avaliacao';

    protected $dates = ['data_hora_inicial', 'data_hora_final', 'data_hora_liberacao_resultado'];

    protected $fillable = [
        'instituicao_id',
        'escola_id',
        'user_id',
        'titulo',
        'data_hora_inicial',
        'data_hora_final',
        'disciplina_id',
        'tipo',
        'aplicacao',
        'ordenacao',
        'tipo_peso',
        'peso',
        'questoes_final',
        'tempo_maximo',
        'data_hora_liberacao_resultado',
        'qtd_questao',
        'publicado',
        'perguntas',
        'eh_ead',
    ];


    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class,'user_id')->withTrashed();
    }

    public function turmas()
    {
        return $this->belongsToMany(FrTurma::class,'fr_avaliacao_turma','avaliacao_id','turma_id')
            ->join('ciclo_etapas', 'ciclo_etapas.id', 'fr_turmas.ciclo_etapa_id')
            ->join('ciclos', 'ciclos.id', 'ciclo_etapas.ciclo_id')
            ->orderBy('ciclo_etapas.titulo')
            ->orderBy('ciclos.titulo')
            ->orderBy('fr_turmas.titulo')
            ->selectRaw('fr_turmas.*, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo');
    }

    public function alunos()
    {
        return $this->belongsToMany(FrTurma::class,'fr_avaliacao_turma','avaliacao_id','turma_id')
            ->join('ciclo_etapas', 'ciclo_etapas.id', 'fr_turmas.ciclo_etapa_id')
            ->join('ciclos', 'ciclos.id', 'ciclo_etapas.ciclo_id')
            ->join('escolas', 'escolas.id', 'fr_turmas.escola_id')
            ->join('fr_turma_aluno', 'fr_turmas.id', 'fr_turma_aluno.turma_id')
            ->join('users', 'users.id', 'fr_turma_aluno.aluno_id')
            ->orderBy('escolas.titulo')
            ->orderBy('ciclo_etapas.titulo')
            ->orderBy('ciclos.titulo')
            ->orderBy('fr_turmas.titulo')
            ->orderBy('users.name')
            ->selectRaw('fr_turmas.*, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo, escolas.titulo as escola, users.nome_completo as aluno');
    }

    public function questao()
    {
        return $this->belongsToMany(FrQuestao::class,'fr_avaliacao_questao','avaliacao_id','questao_id')->orderBy('ordem')->withPivot('peso');
    }

    public function logGeral()
    {
        return $this->hasMany(FrAvaliacaoLogGeral::class,'avaliacao_id');
    }

    public function placar()
    {
        return $this->hasMany(FrAvaliacaoPlacar::class, 'avaliacao_id');
    }

    public function logAtividade()
    {
        return $this->hasMany(FrAvaliacaoLogAtividade::class, 'avaliacao_id');
    }


}
