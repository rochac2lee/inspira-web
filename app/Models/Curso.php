<?php

namespace App\Models;

use App\Models\Conteudo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Curso
 *
 * @property int $id
 * @property int $escola_id
 * @property int $user_id
 * @property int|null $ciclo_id
 * @property int|null $cicloetapa_id
 * @property int|null $disciplina_id
 * @property string|null $tag
 * @property string $titulo
 * @property string $descricao_curta
 * @property string $descricao
 * @property string $capa
 * @property int|null $categoria
 * @property int $tipo
 * @property string|null $visibilidade
 * @property string $senha
 * @property int $status
 * @property float $preco
 * @property int|null $periodo
 * @property int|null $vagas
 * @property string|null $identificador
 * @property string|null $link_checkout
 * @property string|null $data_publicacao
 * @property int $cursos_tipo_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $status_id
 * @property-read \App\Models\Categoria $_categoria
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Aula[] $aulas
 * @property-read int|null $aulas_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AvaliacaoCurso[] $avaliacoes
 * @property-read int|null $avaliacoes_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\AvaliacaoCurso[] $avaliacoes_user
 * @property-read int|null $avaliacoes_user_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Conteudo[] $conteudos
 * @property-read int|null $conteudos_count
 * @property-read \App\Models\Escola $escola
 * @property-read mixed $consumo
 * @property-read mixed $matriculados
 * @property-read mixed $status_name
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Matricula[] $matriculas
 * @property-read int|null $matriculas_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TopicoCurso[] $topicos
 * @property-read int|null $topicos_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Curso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Curso newQuery()
 * @method static \Illuminate\Database\Query\Builder|Curso onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Curso query()
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereCapa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereCategoria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereCicloId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereCicloetapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereCursosTipoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereDataPublicacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereDescricaoCurta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereDisciplinaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereIdentificador($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereLinkCheckout($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso wherePreco($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereSenha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereVagas($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Curso whereVisibilidade($value)
 * @method static \Illuminate\Database\Query\Builder|Curso withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Curso withoutTrashed()
 * @mixin \Eloquent
 */
class Curso extends Model
{
    use SoftDeletes;

    protected $hidden = [];

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'instituicao_id',
        'escola_id',
        'user_id',
        'ciclo_id',
        'cicloetapa_id',
        'disciplina_id',
        'tag',
        'titulo',
        'descricao_curta',
        'descricao',
        'capa',
        'categoria',
        'tipo',
        'visibilidade',
        'senha',
        'status',
        'preco',
        'periodo',
        'vagas',
        'data_publicacao',
        'link_checkout',
        'identificador',
        'cursos_tipo_id',
        'status_id',
        'ead',
        'tipo_permissao',
        'novo',
    ];


    /*
     * FUNCOES DESENVOLVIDAS PELA EDULABS
     */


    //Padrões
    protected $attributes = [
        'escola_id' => 1,
        'capa' => '',
        'categoria' => 1,
        'tipo' => 1,
        'visibilidade' => 1,
        'senha' => '',
        'status' => 0,
        'preco' => 0,
        'periodo' => 0,
        'vagas' => 0,
    ];

    //
    // Tipos
    //
    // 1 = Curso padrão / Para alunos
    // 2 = Curso para professores / gestores (oculto do catalogo)
    //

    //
    // Mutators
    //
    public function getMatriculadosAttribute()
    {
        return Matricula::where('curso_id', '=', $this->id)->count();
    }

    public function getStatusNameAttribute()
    {
        switch ($this->status)
        {
            case 0:
                return 'Rascunho';
                break;
            case 1:
                return 'Publicado';
                break;

            default:
                return 'Rascunho';
                break;
        }
    }

    public function getConsumoAttribute()
    {
        $consumo_total = Conteudo::whereHas('conteudos_aula', function ($query) {
            $query->where('curso_id', '=', $this->id);
        })->sum('file_size');

        $consumo_total = $consumo_total / 1000000; //Convert kb to gb

        return $consumo_total;
    }




    //
    // Relationships
    //

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function escola()
    {
        return $this->belongsTo(Escola::class, 'escola_id');
    }

    public function _categoria()
    {
        return $this->belongsTo(Categoria::class, 'id');
    }

    public function matriculas()
    {
        return $this->hasMany(MAtricula::class, 'curso_id')->whereHas('user');
    }

    public function aulas()
    {
        return $this->hasMany(Aula::class, 'curso_id')->orderBy('ordem', 'asc');
    }

    public function conteudos()
    {
        return $this->hasMany(Conteudo::class, 'curso_id');
    }

    public function avaliacoes()
    {
        return $this->hasMany(AvaliacaoCurso::class, 'curso_id');
    }

    public function avaliacoes_user()
    {
        return $this->hasMany(AvaliacaoCurso::class, 'curso_id')->with('user');
    }

    public function topicos()
    {
        // return $this->hasMany('App\TopicoCurso', 'curso_id')->withCount('comentarios')->withCount('visualizacoes');
        return $this->hasMany(TopicoCurso::class, 'curso_id')->withCount('comentarios');
    }

    public static function TituloUrl($cursos)
    {
        foreach ($cursos as $curso)
        {
            $curso->titulo_url = $curso->titulo;

            if(Curso::where('titulo', '=', $curso->titulo)->count() > 1)
            {
                $curso->titulo_url = $curso->titulo_url . '-' . $curso->id;
            }

            $curso->titulo_url = mb_strtolower($curso->titulo_url, 'UTF-8');
        }

        return $cursos;
    }

    public function curso_user()
    {
        return $this->belongsToMany(User_cuso::class, 'users_escolas', 'curso_id', 'user_id');
    }

    //Função para permissão do usuário que pode enxergar os cursos e filtros
    public static function curso_permissao($cursos, $search, $CursosNotIn){

        $user = auth()->user();

        #mostrando todos os cursos porque os bancos são separados atualmente, mudar isso posteriormente
        $instituicaoUser = InstituicaoUser::where('user_id', $user->id)->first()->instituicao_id;

        $cursos = $cursos->where('cursos.titulo', 'LIKE', '%'.$search.'%');

        if ($CursosNotIn) {
            $cursos
                ->whereNotIn('cursos.id', $CursosNotIn);
        }

        $cursos = $cursos->leftjoin('professor_cursos', 'cursos.id', 'professor_cursos.curso_id')
                            ->leftjoin('responsavel_escolas', 'cursos.escola_id', 'responsavel_escolas.escola_id')
                                ->select('cursos.*');

        switch($user->privilegio_id){
            case 1:
                $cursos = $cursos;
            break;
            case 2:
                $cursos = Curso::where([['escolas.instituicao_id', $instituicaoUser], ['cursos.status_id', 1]])
                        ->leftjoin('escolas', 'cursos.escola_id', 'escolas.id')
                            ->select('cursos.*', 'escolas.instituicao_id');
            break;
            case 3:
                $cursos = $cursos->where('cursos.user_id', $user->id)
                                        ->orwhere('professor_cursos.user_id', $user->id)
                                            ->where('cursos.status_id', 1);
            break;
            case 5:
                $cursos = $cursos->where('cursos.user_id', $user->id)
                                ->orwhere('responsavel_escolas.user_id', $user->id)
                                    ->where('cursos.status_id', 1);
            break;
        }

        #Manter os 2 filtros que evita bugs
        if ($CursosNotIn) {
            $cursos
                ->whereNotIn('cursos.id', $CursosNotIn);
        }
        $cursos = $cursos->where('cursos.titulo', 'LIKE', '%'.$search.'%');

        return $cursos;
    }


}
