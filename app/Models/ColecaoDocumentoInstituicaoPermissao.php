<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoDocumentoInstituicaoPermissao
 *
 * @property int $colecao_id
 * @property int $instituicao_id
 * @property int $cicloetapa_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoDocumentoInstituicaoPermissao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoDocumentoInstituicaoPermissao newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoDocumentoInstituicaoPermissao query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoDocumentoInstituicaoPermissao whereCicloetapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoDocumentoInstituicaoPermissao whereColecaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoDocumentoInstituicaoPermissao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoDocumentoInstituicaoPermissao whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoDocumentoInstituicaoPermissao whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoDocumentoInstituicaoPermissao extends Model
{
    protected $table = 'colecao_documento_instituicao_permissao';

    //Preenchiveis
    protected $fillable = [
        'colecao_id',
        'instituicao_id',
        'cicloetapa_id',
        'todos',
    ];

}
