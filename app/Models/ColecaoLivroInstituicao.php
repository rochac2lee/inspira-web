<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoLivroInstituicao
 *
 * @property int $colecao_id
 * @property int $instituicao_id
 * @property string $todos
 * @property string $todos_periodos
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Conteudo[] $cicloEtapaColecao
 * @property-read int|null $ciclo_etapa_colecao_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Conteudo[] $periodoColecao
 * @property-read int|null $periodo_colecao_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ColecaoLivroInstituicaoPermissao[] $permissao
 * @property-read int|null $permissao_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ColecaoLivroInstituicaoPermissaoPeriodo[] $permissaoPeriodo
 * @property-read int|null $permissao_periodo_count
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicao newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicao query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicao whereColecaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicao whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicao whereTodos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicao whereTodosPeriodos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicao whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoLivroInstituicao extends Model
{
    protected $table = 'colecao_livro_instituicao';

    public $incrementing = false;

    protected $fillable = [
        'colecao_id',
        'instituicao_id',
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
    				->selectRaw('conteudos.colecao_livro_id,conteudos.ciclo_id,conteudos.cicloetapa_id, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo, colecao_livro_instituicao_permissao.cicloetapa_id as permissao');
    }

    public function periodoColecao()
    {
        return $this->hasMany('App\Models\Conteudo', 'colecao_livro_id')
                    ->whereNotNull('conteudos.periodo')
                    ->where('conteudos.periodo','<>','')
                    ->groupBy('conteudos.colecao_livro_id')
                    ->groupBy('conteudos.periodo')
                    ->orderBy('conteudos.periodo')
                    ->selectRaw('conteudos.colecao_livro_id,conteudos.periodo, colecao_livro_instituicao_permissao_periodo.periodo as permissao');
    }

    public function permissao()
    {
        return $this->hasMany('App\Models\ColecaoLivroInstituicaoPermissao', 'colecao_id', 'colecao_id');
    }

    public function permissaoPeriodo()
    {
        return $this->hasMany('App\Models\ColecaoLivroInstituicaoPermissaoPeriodo', 'colecao_id', 'colecao_id');
    }
}
