<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Matricula
 *
 * @property int $user_id
 * @property int $curso_id
 * @property string|null $data_validade
 * @property int $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $status_id
 * @property-read \App\Models\Curso $curso
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula query()
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereCursoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereDataValidade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Matricula whereUserId($value)
 * @mixin \Eloquent
 */
class Matricula extends Model
{
    //Preenchiveis
    protected $fillable = [
        'user_id',
        'curso_id',
        'data_validade',
        'status_id',
    ];

    // Protegidos
    protected $hidden = [
    ];

    //Padrões
    protected $attributes = [
        'status_id' => 1
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->hasMany(Status::class);
    }

}
