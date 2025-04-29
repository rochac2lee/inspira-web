<?php

namespace App\Entities\Trilhas;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Entities\Trilhas\TrilhasMatricula
 *
 * @property int $id
 * @property int $trilha_id
 * @property int $user_id
 * @property int $progresso
 * @property int $qtd_concluido
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Entities\Trilhas\Trilha $trilha
 * @property-read User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasMatricula newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasMatricula newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasMatricula query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasMatricula whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasMatricula whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasMatricula whereProgresso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasMatricula whereQtdConcluido($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasMatricula whereTrilhaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasMatricula whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasMatricula whereUserId($value)
 * @mixin \Eloquent
 */
class TrilhasMatricula extends Model
{
    protected $table = "trilhas_matriculas";

    //Preenchiveis
    protected $fillable = [
        'trilha_id',
        'user_id',
        'progresso',
        'qtd_concluido'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function trilha()
    {
        return $this->belongsTo(Trilha::class, 'trilha_id');
    }
}
