<?php

namespace App\Models\Ead;

use App\Models\CicloEtapa;
use App\Models\Disciplina;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrQuestao[] $questao
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
class Avaliacao extends Model
{
    use SoftDeletes;
    protected $table = 'ead_avaliacao';

    protected $dates = ['data_hora_inicial', 'data_hora_final'];

    protected $fillable = [
        'user_id',
        'titulo',
        'data_hora_inicial',
        'data_hora_final',
        'questoes_final',
        'tempo_maximo',
        'qtd_questao',
        'publicado',
        'perguntas',
        'quantidade_minima_questoes',
        'tipo_peso',
        'peso',
    ];


    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function cicloEtapa()
    {
        return  $this->belongsTo(CicloEtapa::class,'cicloetapa_id')->join('ciclos','ciclos.id','ciclo_etapas.ciclo_id')
            ->selectRaw('ciclo_etapas.id, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo')
            ->where('ciclos.id','<>','1')
            ->where('ciclos.id','<>','5');
    }

    public function questao()
    {
        return $this->belongsToMany(Questao::class,'ead_avaliacao_questao','ead_avaliacao_id','ead_questao_id')
            ->withPivot('peso','ordem')
            ->orderBy('ordem');
    }

    public function logGeral()
    {
        return $this->hasMany(AvaliacaoLogGeral::class,'ead_avaliacao_id');
    }

    public function placar()
    {
        return $this->hasMany(AvaliacaoPlacar::class, 'ead_avaliacao_id');
    }

    public function placarHistorico()
    {
        return $this->hasMany(AvaliacaoPlacarHistorico::class, 'ead_avaliacao_id');
    }

    public function logAtividade()
    {
        return $this->hasMany(AvaliacaoLogAtividade::class, 'ead_avaliacao_id');
    }

}
