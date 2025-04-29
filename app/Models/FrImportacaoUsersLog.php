<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrImportacaoUsersLog
 *
 * @property int $id
 * @property int $importacao_id
 * @property string $erro
 * @property string $erro_validacao
 * @property string $erro_banco
 * @property string|null $nome
 * @property string|null $email
 * @property string|null $escola_id
 * @property string|null $instituicao_id
 * @property string|null $permissao
 * @property string|null $inserir
 * @property int $linha
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog whereErro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog whereErroBanco($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog whereErroValidacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog whereImportacaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog whereInserir($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog whereLinha($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog wherePermissao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUsersLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrImportacaoUsersLog extends Model
{
    protected $table = 'fr_importacao_users_log';

    protected $fillable = [
        'importacao_id',
        'erro',
        'erro_validacao',
        'erro_banco',
        'nome',
        'email',
        'linha',
        'escola_id',
        'instituicao_id',
        'permissao',
        'inserir',
    ];
}
