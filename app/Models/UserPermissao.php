<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * App\Models\UserPermissao
 *
 * @property int $id
 * @property int $user_id
 * @property string $permissao
 * @property int|null $escola_id
 * @property int|null $instituicao_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $matricula
 * @property string|null $ocupacao
 * @property-read \App\Models\Escola|null $escola
 * @property-read \App\Models\Instituicao|null $instituicao
 * @method static Builder|UserPermissao newModelQuery()
 * @method static Builder|UserPermissao newQuery()
 * @method static Builder|UserPermissao query()
 * @method static Builder|UserPermissao whereCreatedAt($value)
 * @method static Builder|UserPermissao whereEscolaId($value)
 * @method static Builder|UserPermissao whereId($value)
 * @method static Builder|UserPermissao whereInstituicaoId($value)
 * @method static Builder|UserPermissao whereMatricula($value)
 * @method static Builder|UserPermissao whereOcupacao($value)
 * @method static Builder|UserPermissao wherePermissao($value)
 * @method static Builder|UserPermissao whereUpdatedAt($value)
 * @method static Builder|UserPermissao whereUserId($value)
 * @mixin \Eloquent
 */
class UserPermissao extends Model
{

    protected $table = 'user_permissao';

    protected $fillable = [
        'user_id',
        'escola_id',
        'instituicao_id',
        'permissao',
        'matricula',
        'ocupacao',
    ];


    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('ativos', function (Builder $builder) {
            $builder->join('instituicao', 'instituicao.id', 'user_permissao.instituicao_id')
                    ->where('instituicao.status_id',1)
                    ->selectRaw('user_permissao.*');
        });
    }


    public function escola()
    {
        return $this->hasOne('App\Models\Escola','id','escola_id');
    }

    public function instituicao()
    {
        return $this->hasOne('App\Models\Instituicao','id','instituicao_id');
    }
}
