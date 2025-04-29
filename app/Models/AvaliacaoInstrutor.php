<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AvaliacaoInstrutor
 *
 * @property int $user_id
 * @property int $instrutor_id
 * @property string $descricao
 * @property float $avaliacao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $instrutor
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoInstrutor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoInstrutor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoInstrutor query()
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoInstrutor whereAvaliacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoInstrutor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoInstrutor whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoInstrutor whereInstrutorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoInstrutor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AvaliacaoInstrutor whereUserId($value)
 * @mixin \Eloquent
 */
class AvaliacaoInstrutor extends Model
{
    protected $table = 'avaliacoes_instrutor';

    //Preenchiveis
    protected $fillable = [
        'user_id',
        'instrutor_id',
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

    public function instrutor()
    {
        return $this->belongsTo(User::class, 'instrutor_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
