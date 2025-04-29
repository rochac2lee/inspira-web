<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoAudioInstituicao
 *
 * @property int $colecao_id
 * @property int $instituicao_id
 * @property string $todos
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Conteudo[] $cicloEtapaColecao
 * @property-read int|null $ciclo_etapa_colecao_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ColecaoAudioInstituicaoPermissao[] $permissao
 * @property-read int|null $permissao_count
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicao newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicao query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicao whereColecaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicao whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicao whereTodos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicao whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoAudioInstituicao extends Model
{
    protected $table = 'colecao_audio_instituicao';


    public $incrementing = false;

    protected $fillable = [
        'colecao_id',
        'instituicao_id',
        'todos',
    ];


    public function cicloEtapaColecao()
    {
    	return $this->hasMany('App\Models\Conteudo', 'colecao_livro_id')
                    ->groupBy('conteudos.colecao_livro_id')
    				->groupBy('conteudos.cicloetapa_id')
    				->join('ciclo_etapas','ciclo_etapas.id','conteudos.cicloetapa_id')
    				->join('ciclos','ciclos.id','ciclo_etapas.ciclo_id')

    				->selectRaw('conteudos.colecao_livro_id,conteudos.ciclo_id,conteudos.cicloetapa_id, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo, colecao_audio_instituicao_permissao.cicloetapa_id as permissao');
    }

    public function permissao()
    {
        return $this->hasMany('App\Models\ColecaoAudioInstituicaoPermissao', 'colecao_id', 'colecao_id');
    }
}
