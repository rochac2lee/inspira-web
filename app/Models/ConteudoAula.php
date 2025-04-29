<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;


/**
 * App\Models\ConteudoAula
 *
 * @property int $id
 * @property int $ordem
 * @property int $conteudo_id
 * @property int $curso_id
 * @property int $aula_id
 * @property int $user_id
 * @property int $obrigatorio
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Conteudo $conteudo
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoAula newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoAula newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoAula query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoAula whereAulaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoAula whereConteudoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoAula whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoAula whereCursoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoAula whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoAula whereObrigatorio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoAula whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoAula whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConteudoAula whereUserId($value)
 * @mixin \Eloquent
 */
class ConteudoAula extends Model
{
    protected $table = 'conteudo_aula';

    //Preenchiveis
    protected $fillable = [
        'id',
        'ordem',
        'curso_id',
        'aula_id',
        'conteudo_id',
        'user_id',
        'obrigatorio',
    ];

    public function conteudo()
    {
        return $this->belongsTo(Conteudo::class, 'conteudo_id');//->where([['curso_id', '=', $this->curso_id], ['aula_id', '=', $this->aula_id]]);
    }

    public function progressos()
    {
        return $this->hasMany(ProgressoConteudo::class, 'conteudo_id')->where('tipo', '=', 2);
    }

    public function progressos_user()
    {
        return $this->hasMany(ProgressoConteudo::class, 'conteudo_id')->with('user')->where('tipo', '=', 2);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function favorito($idUser, $idRef)
    {
        return $this->hasOne(Favorito::class, 'referencia_id')
            ->where([['user_id', $idUser], ['referencia_id', $idRef], ['tipo', 'conteudo_aula']])->first();
    }

}
