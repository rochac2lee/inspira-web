<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\InstituicaoUser
 *
 * @property int $id
 * @property int $instituicao_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Instituicao $instituicao
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoUser whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoUser whereUserId($value)
 * @mixin \Eloquent
 */
class InstituicaoUser extends Model
{
    #Model para relacionamento de instituicao com usuarios
    protected $table = 'instituicao_users';

    //Preenchiveis
    protected $fillable = [
        'instituicao_id',
        'user_id'
    ];

    // Protegidos
    protected $hidden = [];

    //Padrões
    protected $attributes = [];

    public function instituicao()
    {
        return $this->belongsTo(Instituicao::class, 'instituicao_id');
    }

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

}
