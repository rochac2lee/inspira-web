<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AvaliacaoCurso
 *
 * @property int $user_id
 * @property int $curso_id
 * @property string $descricao
 * @property float $avaliacao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Curso $curso
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoCurso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoCurso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoCurso query()
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoCurso whereAvaliacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoCurso whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoCurso whereCursoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoCurso whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoCurso whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoCurso whereUserId($value)
 * @mixin \Eloquent
 */
class AvaliacaoCurso extends Model
{
    protected $table = 'avaliacoes_curso';

    //Preenchiveis
    protected $fillable = [
        'user_id',
        'curso_id',
        'avaliacao',
        'descricao',
    ];

    //Padrões
    protected $attributes = [
        'descricao' => '',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
