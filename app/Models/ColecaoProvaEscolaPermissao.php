<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoProvaEscolaPermissao
 *
 * @property int $colecao_id
 * @property int $escola_id
 * @property int $cicloetapa_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscolaPermissao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscolaPermissao newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscolaPermissao query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscolaPermissao whereCicloetapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscolaPermissao whereColecaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscolaPermissao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscolaPermissao whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaEscolaPermissao whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoProvaEscolaPermissao extends Model
{
    protected $table = 'colecao_prova_escola_permissao';

    //Preenchiveis
    protected $fillable = [
        'colecao_id',
        'escola_id',
        'cicloetapa_id',
        'todos',
    ];

}
