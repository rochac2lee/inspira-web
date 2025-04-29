<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoLivroEscola
 *
 * @property int $colecao_id
 * @property int $escola_id
 * @property string $todos
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $todos_periodos
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Conteudo[] $cicloEtapaColecao
 * @property-read int|null $ciclo_etapa_colecao_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Conteudo[] $periodoColecao
 * @property-read int|null $periodo_colecao_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ColecaoLivroEscolaPermissao[] $permissao
 * @property-read int|null $permissao_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ColecaoLivroEscolaPermissaoPeriodo[] $permissaoPeriodo
 * @property-read int|null $permissao_periodo_count
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscola newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscola newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscola query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscola whereColecaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscola whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscola whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscola whereTodos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscola whereTodosPeriodos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscola whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoLivroEscola extends Model
{
    protected $table = 'colecao_livro_escola';

   //	protected $primaryKey = ['colecao_id', 'escola_id'];

    public $incrementing = false;

    protected $fillable = [
        'colecao_id',
        'escola_id',
        'todos',
        'todos_periodos',
    ];


    public function cicloEtapaColecao()
    {
    	return $this->hasMany('App\Models\Conteudo', 'colecao_livro_id')
                    ->groupBy('conteudos.colecao_livro_id')
    				->groupBy('conteudos.cicloetapa_id')
    				->join('ciclo_etapas','ciclo_etapas.id','conteudos.cicloetapa_id')
    				->join('ciclos','ciclos.id','ciclo_etapas.ciclo_id')
    				->selectRaw('conteudos.colecao_livro_id,conteudos.ciclo_id,conteudos.cicloetapa_id, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo, colecao_livro_escola_permissao.cicloetapa_id as permissao');
    }

    public function periodoColecao()
    {
        return $this->hasMany('App\Models\Conteudo', 'colecao_livro_id')
                    ->whereNotNull('conteudos.periodo')
                    ->where('conteudos.periodo','<>','')
                    ->groupBy('conteudos.colecao_livro_id')
                    ->groupBy('conteudos.periodo')
                    ->orderBy('conteudos.periodo')
                    ->selectRaw('conteudos.colecao_livro_id,conteudos.periodo, colecao_livro_escola_permissao_periodo.periodo as permissao');
    }

    public function permissao()
    {
        return $this->hasMany('App\Models\ColecaoLivroEscolaPermissao', 'colecao_id', 'colecao_id');
    }

    public function permissaoPeriodo()
    {
        return $this->hasMany('App\Models\ColecaoLivroEscolaPermissaoPeriodo', 'colecao_id', 'colecao_id');
    }
}
