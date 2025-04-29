<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AvaliacaoConteudo
 *
 * @property int $id
 * @property int $user_id
 * @property int $conteudo_id
 * @property int $avaliacao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Conteudo $conteudo
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoConteudo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoConteudo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoConteudo query()
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoConteudo whereAvaliacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoConteudo whereConteudoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoConteudo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoConteudo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoConteudo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoConteudo whereUserId($value)
 * @mixin \Eloquent
 */
class AvaliacaoConteudo extends Model
{
    protected $table = 'avaliacoes_conteudo';

    //Preenchiveis
    protected $fillable = [
        'user_id',
        'conteudo_id',
        'avaliacao',
    ];

    // Protegidos
    protected $hidden = [
    ];

    //Padrões
    protected $attributes = [
        'avaliacao' => 0,
    ];

    public function conteudo()
    {
        return $this->belongsTo(Conteudo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
