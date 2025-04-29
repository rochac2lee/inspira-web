<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CursosPermissoes
 *
 * @property int $id
 * @property int $curso_id
 * @property string $permissao
 * @property string $status
 * @property int $escola_id
 * @property int $instituicao_id
 * @property int $trilha_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CursosPermissoes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CursosPermissoes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CursosPermissoes query()
 * @method static \Illuminate\Database\Eloquent\Builder|CursosPermissoes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CursosPermissoes whereCursoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CursosPermissoes whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CursosPermissoes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CursosPermissoes whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CursosPermissoes wherePermissao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CursosPermissoes whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CursosPermissoes whereTrilhaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CursosPermissoes whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CursosPermissoes extends Model
{
    //Permissoes de cursos
    protected $table = 'cursos_permissoes';

    //Preenchiveis
    protected $fillable = [
        'curso_id',
        'permissao',
        'status',
        'escola_id',
        'instituicao_id',
        'trilha_id',
    ];
}

