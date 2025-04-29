<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CursoCompleto
 *
 * @property int $id
 * @property int $curso_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Curso $curso
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|CursoCompleto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CursoCompleto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CursoCompleto query()
 * @method static \Illuminate\Database\Eloquent\Builder|CursoCompleto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CursoCompleto whereCursoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CursoCompleto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CursoCompleto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CursoCompleto whereUserId($value)
 * @mixin \Eloquent
 */
class CursoCompleto extends Model
{
    protected $table = 'curso_completo';

    //Preenchiveis
    protected $fillable = [
        'curso_id',
        'user_id',
    ];

    // Protegidos
    protected $hidden = [
    ];

    //Padrões
    protected $attributes = [
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

}
