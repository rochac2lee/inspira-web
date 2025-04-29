<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProfessorCurso
 *
 * @property int $id
 * @property int $user_id
 * @property int $escola_id
 * @property int $curso_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Curso[] $curso
 * @property-read int|null $curso_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 * @property-read int|null $user_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorCurso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorCurso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorCurso query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorCurso whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorCurso whereCursoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorCurso whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorCurso whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorCurso whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorCurso whereUserId($value)
 * @mixin \Eloquent
 */
class ProfessorCurso extends Model
{
    protected $table = 'professor_cursos';

    protected $fillable = [
        'user_id',
        'escola_id',
        'curso_id',
    ];

    protected $hidden = [
    ];

    public function user(){
        return $this->belongsToMany(User::class);
    }

    public function curso(){
        return $this->belongsToMany(Curso::class);
    }
}
