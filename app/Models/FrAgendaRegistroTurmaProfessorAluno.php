<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaRegistroTurmaProfessorAluno
 *
 * @property int $id
 * @property int $aluno_id
 * @property int $registro_turma_id
 * @property string|null $marcado
 * @property string|null $texto
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroTurmaProfessorAluno newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroTurmaProfessorAluno newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroTurmaProfessorAluno query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroTurmaProfessorAluno whereAlunoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroTurmaProfessorAluno whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroTurmaProfessorAluno whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroTurmaProfessorAluno whereMarcado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroTurmaProfessorAluno whereRegistroTurmaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroTurmaProfessorAluno whereTexto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroTurmaProfessorAluno whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrAgendaRegistroTurmaProfessorAluno extends Model
{
    protected $table = 'fr_agenda_registros_turma_professor_aluno';

    protected $fillable = [
        'aluno_id',
        'registro_turma_id',
        'marcado',
        'texto',
    ];

}
