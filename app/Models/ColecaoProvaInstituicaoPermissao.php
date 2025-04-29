<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoProvaInstituicaoPermissao
 *
 * @property int $colecao_id
 * @property int $instituicao_id
 * @property int $cicloetapa_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaInstituicaoPermissao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaInstituicaoPermissao newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaInstituicaoPermissao query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaInstituicaoPermissao whereCicloetapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaInstituicaoPermissao whereColecaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaInstituicaoPermissao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaInstituicaoPermissao whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoProvaInstituicaoPermissao whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoProvaInstituicaoPermissao extends Model
{
    protected $table = 'colecao_prova_instituicao_permissao';

    //Preenchiveis
    protected $fillable = [
        'colecao_id',
        'instituicao_id',
        'cicloetapa_id',
        'todos',
    ];

}
