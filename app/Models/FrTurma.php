<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\FrTurma
 *
 * @property int $id
 * @property string $titulo
 * @property int $escola_id
 * @property int $ciclo_etapa_id
 * @property string|null $rotina_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $alunos
 * @property-read int|null $alunos_count
 * @property-read \App\Models\CicloEtapa $cicloEtapa
 * @property-read \App\Models\Escola $escola
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $professores
 * @property-read int|null $professores_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $qtdAlunos
 * @property-read int|null $qtd_alunos_count
 * @method static \Illuminate\Database\Eloquent\Builder|FrTurma newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrTurma newQuery()
 * @method static \Illuminate\Database\Query\Builder|FrTurma onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FrTurma query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrTurma whereCicloEtapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrTurma whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrTurma whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrTurma whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrTurma whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrTurma whereRotinaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrTurma whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrTurma whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|FrTurma withTrashed()
 * @method static \Illuminate\Database\Query\Builder|FrTurma withoutTrashed()
 * @mixin \Eloquent
 */
class FrTurma extends Model
{
    protected $table = 'fr_turmas';

    use SoftDeletes;

    protected $fillable = [
        'titulo',
        'turno',
        'escola_id',
        'ciclo_etapa_id',
        'rotina_id',
    ];

    public function escola()
    {
        return  $this->belongsTo(Escola::class);
    }

    public function cicloEtapa()
    {
        return  $this->belongsTo(CicloEtapa::class,'ciclo_etapa_id')->join('ciclos','ciclos.id','ciclo_etapas.ciclo_id')
            ->selectRaw('ciclo_etapas.id, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo');
    }

    public function professores()
    {
        return $this->belongsToMany(User::class,'fr_turma_professor','turma_id','professor_id')->orderBy('nome_completo');
    }

    public function alunos()
    {
        return $this->belongsToMany(User::class,'fr_turma_aluno','turma_id','aluno_id')->orderBy('nome_completo');
    }

    public function qtdAlunos()
    {
        return $this->belongsToMany(User::class,'fr_turma_aluno','turma_id','aluno_id')
            ->selectRaw('count(id) as qtd, id');
    }

}
