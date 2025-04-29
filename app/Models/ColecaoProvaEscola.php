<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoProvaEscola
 *
 * @property int $colecao_id
 * @property int $escola_id
 * @property string $todos
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Conteudo[] $cicloEtapaColecao
 * @property-read int|null $ciclo_etapa_colecao_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ColecaoProvaEscolaPermissao[] $permissao
 * @property-read int|null $permissao_count
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscola newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscola newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscola query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscola whereColecaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscola whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscola whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscola whereTodos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscola whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoProvaEscola extends Model
{
    protected $table = 'colecao_prova_escola';

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

    				->selectRaw('conteudos.colecao_livro_id,conteudos.ciclo_id,conteudos.cicloetapa_id, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo, colecao_prova_escola_permissao.cicloetapa_id as permissao');
    }

    public function permissao()
    {
        return $this->hasMany('App\Models\ColecaoProvaEscolaPermissao', 'colecao_id', 'colecao_id');
    }
}
