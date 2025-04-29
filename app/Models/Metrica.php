<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Metrica
 *
 * @property int $id
 * @property int $user_id
 * @property string $titulo
 * @property string|null $descricao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Metrica newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Metrica newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Metrica query()
 * @method static \Illuminate\Database\Eloquent\Builder|Metrica whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metrica whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metrica whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metrica whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metrica whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Metrica whereUserId($value)
 * @mixin \Eloquent
 */
class Metrica extends Model
{
    //Preenchiveis
    protected $fillable = [
        'id',
        'user_id',
        'titulo',
        'descricao',
    ];

    // Protegidos
    protected $hidden = [
    ];

    //Padrões
    protected $attributes = [
        'descricao' => '',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function aluno_turma()
    {
        return $this->belongsTo(AlunoTurma::class, 'user_id', 'user_id');
    }

}
