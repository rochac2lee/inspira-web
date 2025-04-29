<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ProfessorEscola
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $escola_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Escola[] $escola
 * @property-read int|null $escola_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 * @property-read int|null $user_count
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorEscola newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorEscola newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorEscola query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorEscola whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorEscola whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorEscola whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorEscola whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProfessorEscola whereUserId($value)
 * @mixin \Eloquent
 */
class ProfessorEscola extends Model
{
    protected $table = 'professor_escolas';

    protected $fillable = [
        'user_id',
        'escola_id',
    ];

    protected $hidden = [];

    public function user(){
        return $this->belongsToMany(User::class);
    }

    public function escola(){
        return $this->belongsToMany(Escola::class);
    }
}

