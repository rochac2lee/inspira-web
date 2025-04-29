<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CiclosPermissoes
 *
 * @property int $id
 * @property int $ciclo_id
 * @property string $permissao
 * @property string $status
 * @property int $escola_id
 * @property int $instituicao_id
 * @property int $trilha_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CiclosPermissoes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CiclosPermissoes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CiclosPermissoes query()
 * @method static \Illuminate\Database\Eloquent\Builder|CiclosPermissoes whereCicloId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CiclosPermissoes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CiclosPermissoes whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CiclosPermissoes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CiclosPermissoes whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CiclosPermissoes wherePermissao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CiclosPermissoes whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CiclosPermissoes whereTrilhaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CiclosPermissoes whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CiclosPermissoes extends Model
{
    //Model de trilhas permissoes
    protected $table = 'ciclos_permissoes';

    protected $fillable = [
        'ciclo_id',
        'permissao',
        'status',
        'escola_id',
        'instituicao_id',
        'trilha_id',
    ];
}

