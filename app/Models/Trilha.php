<?php

namespace App\Models;

use App\Models\Altitude\TrilhasInstituicaoEscolaBiblioteca;
use App\Models\Altitude\TrilhasInstituicaoEscolaRealizar;
use App\Models\Altitude\TrilhasMatriculasUsuario;
use App\Models\Ead\Avaliacao;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Trilha
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $instituicao_id
 * @property int|null $ciclo_id
 * @property int|null $cicloetapa_id
 * @property int|null $disciplina_id
 * @property int|null $visibilidade_id
 * @property string|null $tag
 * @property string $titulo
 * @property string|null $capa
 * @property string|null $descricao
 * @property string $para_professor
 * @property string $publicado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $escola_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $status_id
 * @property int|null $ordem
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Curso[] $cursos
 * @property-read int|null $cursos_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\TrilhasCurso[] $trilhas_cursos
 * @property-read int|null $trilhas_cursos_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha newQuery()
 * @method static \Illuminate\Database\Query\Builder|Trilha onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha query()
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereCapa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereCicloId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereCicloetapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereDisciplinaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereParaProfessor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha wherePublicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereTag($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Trilha whereVisibilidadeId($value)
 * @method static \Illuminate\Database\Query\Builder|Trilha withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Trilha withoutTrashed()
 * @mixin \Eloquent
 */
class Trilha extends Model
{
    protected $table = "trilhas";

    use SoftDeletes;

    //Preenchiveis
    protected $fillable = [
        'instituicao_id',
        'user_id',
        'ciclo_id',
        'cicloetapa_id',
        'disciplina_id',
        'visibilidade_id',
        'escola_id',
        'tag',
        'titulo',
        'capa',
        'descricao',
        'tag',
        'autor',
        'status_id',
        'criado_por_opet',
        'para_professor',
        'nova',
        'ead',
        'ead_matricula_inicio',
        'ead_matricula_fim',
        'carga_horaria',
        'avaliacao_id',
        'permite_biblioteca',
        'tipo_permissao_biblioteca',
        'tipo_permissao_realizar',
        'perfil_permissao_realizar',
        'publicado',
    ];

    protected $dates = ['deleted_at'];

    // Protegidos
    protected $hidden = [
    ];
    /* FUNCOES F&R */
    public function getUrlCapaAttribute(){
        return config('app.cdn').'/storage/uploads/trilhas/capas/'.$this->capa;
    }

    public function matriculados()
    {
        return $this->hasMany(TrilhasMatriculasUsuario::class, 'trilha_id');
    }

    public function avaliacao()
    {
        return $this->hasOne(Avaliacao::class, 'id', 'avaliacao_id');
    }

    /* FUNCOES ANTIGAS DA EDULABS */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function matriculas()
    {
        //return $this->hasMany(TrilhasMatricula::class, 'trilha_id'); EduLaBzz

        return $this->belongsToMany(User::class, 'trilha_usuario_matricula', 'trilha_id', 'user_id')->withPivot(['porcentagem_acerto', 'nota', 'chave_validacao', 'tentativas_avaliacao']);
    }

    public function trilhas_cursos()
    {
        return $this->hasMany(TrilhasCurso::class, 'trilha_id');
    }

    public function cursos()
    {
        return $this->belongsToMany(\App\Models\Altitude\Curso::class, 'trilhas_cursos', 'trilha_id')->orderByPivot('ordem');
    }

    public function escolasEAD()
    {
        return $this->belongsToMany(Escola::class,'trilhas_instituicao_escola_realizar','trilha_id','escola_id')
            ->withPivot(['escola_id','instituicao_id', 'publico', 'privado']);
    }

    public function escolasBiblioteca()
    {
        return $this->belongsToMany(Escola::class,'trilhas_instituicao_escola_biblioteca','trilha_id','escola_id')
            ->withPivot(['escola_id','instituicao_id', 'publico', 'privado']);
    }

    public function permissaoEad()
    {
        return $this->hasMany(TrilhasInstituicaoEscolaRealizar::class, 'trilha_id');
    }

    public function permissaoBiblioteca()
    {
        return $this->hasMany(TrilhasInstituicaoEscolaBiblioteca::class, 'trilha_id');
    }
}
