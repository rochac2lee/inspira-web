<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CicloEtapasPermissoes
 *
 * @property int $id
 * @property int $ciclo_etapa_id
 * @property string $permissao
 * @property string $status
 * @property int $escola_id
 * @property int $instituicao_id
 * @property int $trilha_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapasPermissoes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapasPermissoes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapasPermissoes query()
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapasPermissoes whereCicloEtapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapasPermissoes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapasPermissoes whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapasPermissoes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapasPermissoes whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapasPermissoes wherePermissao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapasPermissoes whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapasPermissoes whereTrilhaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapasPermissoes whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CicloEtapasPermissoes extends Model
{
    //Model de trilhas permissoes
    protected $table = 'ciclo_etapas_permissoes';

    protected $fillable = [
        'ciclo_etapa_id',
        'permissao',
        'status',
        'escola_id',
        'instituicao_id',
        'trilha_id',
    ];
}
