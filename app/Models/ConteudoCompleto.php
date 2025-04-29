<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ConteudoCompleto
 *
 * @property int $id
 * @property int $curso_id
 * @property int $aula_id
 * @property int $conteudo_id
 * @property int $user_id
 * @property string|null $resposta
 * @property string|null $correta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Curso $curso
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoCompleto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoCompleto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoCompleto query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoCompleto whereAulaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoCompleto whereConteudoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoCompleto whereCorreta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoCompleto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoCompleto whereCursoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoCompleto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoCompleto whereResposta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoCompleto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoCompleto whereUserId($value)
 * @mixin \Eloquent
 */
class ConteudoCompleto extends Model
{
    protected $table = 'conteudo_completo';

    //Preenchiveis
    protected $fillable = [
        'id',
        'curso_id',
        'aula_id',
        'conteudo_id',
        'user_id',
        'resposta',
        'correta',
    ];

    // Protegidos
    protected $hidden = [
    ];

    //Padrões
    protected $attributes = [
        'resposta' => null,
        'correta' => null
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
