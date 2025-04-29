<?php

namespace App\Entities\Trilhas;

use App\Models\Curso;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Entities\Trilhas\TrilhasCurso
 *
 * @property int $id
 * @property int $trilha_id
 * @property int $curso_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $ordem
 * @property-read Curso $curso
 * @property-read \App\Entities\Trilhas\Trilha $trilha
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasCurso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasCurso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasCurso query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasCurso whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasCurso whereCursoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasCurso whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasCurso whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasCurso whereTrilhaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasCurso whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrilhasCurso extends Model
{
    protected $table = "trilhas_cursos";

    //Preenchiveis
    protected $fillable = [
        'trilha_id',
        'curso_id',
        'ordem'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function trilha()
    {
        return $this->belongsTo(Trilha::class, 'trilha_id');
    }
}
