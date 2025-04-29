<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoAudioEscola
 *
 * @property int $colecao_id
 * @property int $escola_id
 * @property string $todos
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Conteudo[] $cicloEtapaColecao
 * @property-read int|null $ciclo_etapa_colecao_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ColecaoAudioEscolaPermissao[] $permissao
 * @property-read int|null $permissao_count
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscola newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscola newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscola query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscola whereColecaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscola whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscola whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscola whereTodos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscola whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoAudioEscola extends Model
{
    protected $table = 'colecao_audio_escola';

   //	protected $primaryKey = ['colecao_id', 'escola_id'];

    public $incrementing = false;

    protected $fillable = [
        'colecao_id',
        'escola_id',
        'todos',
    ];


    public function cicloEtapaColecao()
    {
    	return $this->hasMany('App\Models\Conteudo', 'colecao_livro_id')
                    ->groupBy('conteudos.colecao_livro_id')
    				->groupBy('conteudos.cicloetapa_id')
    				->join('ciclo_etapas','ciclo_etapas.id','conteudos.cicloetapa_id')
    				->join('ciclos','ciclos.id','ciclo_etapas.ciclo_id')

    				->selectRaw('conteudos.colecao_livro_id,conteudos.ciclo_id,conteudos.cicloetapa_id, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo, colecao_audio_escola_permissao.cicloetapa_id as permissao');
    }

    public function permissao()
    {
        return $this->hasMany('App\Models\ColecaoAudioEscolaPermissao', 'colecao_id', 'colecao_id');
    }
}
