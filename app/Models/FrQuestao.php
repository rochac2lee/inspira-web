<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrQuestao
 *
 * @property int $id
 * @property int $user_id
 * @property int $instituicao_id
 * @property int $escola_id
 * @property int $disciplina_id
 * @property string $dificuldade
 * @property string $tipo
 * @property string $pergunta
 * @property string|null $alternativa_1
 * @property string|null $alternativa_2
 * @property string|null $alternativa_3
 * @property string|null $alternativa_4
 * @property string|null $alternativa_5
 * @property string|null $alternativa_6
 * @property string|null $alternativa_7
 * @property int $qtd_alternativa
 * @property int|null $correta
 * @property string $com_linhas
 * @property int|null $qtd_linhas
 * @property string $disponibilizar_resolucao
 * @property string|null $resolucao
 * @property string|null $link_video_resolucao
 * @property string $compartilhar
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $bncc_id
 * @property int|null $cicloetapa_id
 * @property string|null $unidade_tematica
 * @property string $revisado
 * @property string $cadastro
 * @property int|null $suporte_id
 * @property int|null $formato_id
 * @property string|null $palavras_chave
 * @property string|null $fonte
 * @property int|null $tema_id
 * @property string $publicado
 * @property int|null $id_original
 * @property-read \App\Models\FrBncc|null $bncc
 * @property-read \App\Models\CicloEtapa|null $cicloEtapa
 * @property-read \App\Models\Disciplina $disciplina
 * @property-read \App\Models\User $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereAlternativa1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereAlternativa2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereAlternativa3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereAlternativa4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereAlternativa5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereAlternativa6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereAlternativa7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereBnccId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereCadastro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereCicloetapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereComLinhas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereCompartilhar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereCorreta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereDificuldade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereDisciplinaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereDisponibilizarResolucao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereFonte($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereFormatoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereIdOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereLinkVideoResolucao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao wherePalavrasChave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao wherePergunta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao wherePublicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereQtdAlternativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereQtdLinhas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereResolucao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereRevisado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereSuporteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereTemaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereUnidadeTematica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereUserId($value)
 * @mixin \Eloquent
 */
class FrQuestao extends Model
{
    protected $table = 'fr_questao';

    protected $fillable = [

        'user_id',
        'instituicao_id',
        'escola_id',
        'disciplina_id',
        'dificuldade',
        'tipo',
        'pergunta',
        'alternativa_1',
        'alternativa_2',
        'alternativa_3',
        'alternativa_4',
        'alternativa_5',
        'alternativa_6',
        'alternativa_7',
        'qtd_alternativa',
        'correta',
        'com_linhas',
        'qtd_linhas',
        'disponibilizar_resolucao',
        'resolucao',
        'link_video_resolucao',
        'compartilhar',
        'bncc_id',
        'cicloetapa_id',
        'unidade_tematica',
        'cadastro',
        'revisado',
        'suporte_id',
        'formato_id',
        'tema_id',
        'palavras_chave',
        'fonte',
        'publicado',
        'id_original',
        'eh_ead',
        'full_text',

    ];

    public function usuario()
    {
        return $this->belongsTo(User::class,'user_id')->withTrashed();
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function bncc()
    {
        return $this->belongsTo(FrBncc::class);
    }

    public function cicloEtapa()
    {
       return  $this->belongsTo(CicloEtapa::class,'cicloetapa_id')->join('ciclos','ciclos.id','ciclo_etapas.ciclo_id')
                    ->selectRaw('ciclo_etapas.id, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo')
                    ->where('ciclos.id','<>','1')
                    ->where('ciclos.id','<>','5');
    }

}
