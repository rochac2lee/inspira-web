<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TrilhasPermissoes
 *
 * @property int $id
 * @property int $trilha_id
 * @property string $permissao
 * @property string $status
 * @property int $escola_id
 * @property int $instituicao_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasPermissoes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasPermissoes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasPermissoes query()
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasPermissoes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasPermissoes whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasPermissoes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasPermissoes whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasPermissoes wherePermissao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasPermissoes whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasPermissoes whereTrilhaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TrilhasPermissoes whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TrilhasPermissoes extends Model
{
    //Model de trilhas permissoes
    protected $table = 'trilhas_permissoes';

    protected $fillable = [
        'trilha_id',
        'permissao',
        'status',
        'escola_id',
        'instituicao_id',
    ];
}
