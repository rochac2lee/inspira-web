<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AvaliacaoEscola
 *
 * @property int $user_id
 * @property int $escola_id
 * @property string $descricao
 * @property float $avaliacao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Escola $escola
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoEscola newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoEscola newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoEscola query()
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoEscola whereAvaliacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoEscola whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoEscola whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoEscola whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoEscola whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoEscola whereUserId($value)
 * @mixin \Eloquent
 */
class AvaliacaoEscola extends Model
{
    protected $table = 'avaliacoes_escola';

    //Preenchiveis
    protected $fillable = [
        'user_id',
        'escola_id',
        'avaliacao',
        'descricao',
    ];

    // Protegidos
    protected $hidden = [
    ];

    //Padrões
    protected $attributes = [
        'descricao' => '',
    ];

    public function escola()
    {
        return $this->belongsTo(Escola::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
