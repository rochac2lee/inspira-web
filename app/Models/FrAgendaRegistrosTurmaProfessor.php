<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaRegistrosTurmaProfessor
 *
 * @property int $id
 * @property int $registro_id
 * @property int $turma_id
 * @property int $professor_id
 * @property string $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $alunos
 * @property-read int|null $alunos_count
 * @property-read \App\Models\FrAgendaRegistroRotina|null $registro
 * @property-read \Illuminate\Database\Eloquent\Collection|FrAgendaRegistrosTurmaProfessor[] $registroData
 * @property-read int|null $registro_data_count
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistrosTurmaProfessor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistrosTurmaProfessor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistrosTurmaProfessor query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistrosTurmaProfessor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistrosTurmaProfessor whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistrosTurmaProfessor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistrosTurmaProfessor whereProfessorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistrosTurmaProfessor whereRegistroId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistrosTurmaProfessor whereTurmaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistrosTurmaProfessor whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrAgendaRegistrosTurmaProfessor extends Model
{
    protected $table = 'fr_agenda_registros_turma_professor';

       protected $fillable = [
        'registro_id',
        'turma_id',
        'professor_id',
        'data',
    ];

    public function alunos()
    {
        return $this->belongsToMany(User::class,'fr_agenda_registros_turma_professor_aluno','registro_turma_id','aluno_id')->orderBy('nome_completo')
            ->withPivot(['marcado','texto']);
    }

    public function registro(){
        return $this->hasOne(FrAgendaRegistroRotina::class,'id','registro_id');
    }


    public function registroData()
    {
        return $this->hasMany(FrAgendaRegistrosTurmaProfessor::class,'data','data');
    }
}
