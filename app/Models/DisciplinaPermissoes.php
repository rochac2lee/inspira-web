<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\DisciplinaPermissoes
 *
 * @property int $id
 * @property int $disciplina_id
 * @property string $permissao
 * @property string $status
 * @property int $escola_id
 * @property int $instituicao_id
 * @property int $trilha_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|DisciplinaPermissoes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisciplinaPermissoes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DisciplinaPermissoes query()
 * @method static \Illuminate\Database\Eloquent\Builder|DisciplinaPermissoes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisciplinaPermissoes whereDisciplinaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisciplinaPermissoes whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisciplinaPermissoes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisciplinaPermissoes whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisciplinaPermissoes wherePermissao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisciplinaPermissoes whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisciplinaPermissoes whereTrilhaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DisciplinaPermissoes whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class DisciplinaPermissoes extends Model
{
    //Model de trilhas permissoes
    protected $table = 'disciplina_permissoes';

    protected $fillable = [
        'disciplina_id',
        'ciclo_id',
        'permissao',
        'status',
        'escola_id',
        'instituicao_id',
        'trilha_id',
    ];
}
