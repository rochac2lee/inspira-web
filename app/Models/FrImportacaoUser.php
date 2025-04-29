<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrImportacaoUser
 *
 * @property int $id
 * @property string $arquivo
 * @property int $user_id
 * @property int $tipo_arquivo
 * @property string|null $erros
 * @property string|null $acertos
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $qtd_certo
 * @property int|null $qtd_errado
 * @property string|null $nome_original
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUser whereAcertos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUser whereArquivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUser whereErros($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUser whereNomeOriginal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUser whereQtdCerto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUser whereQtdErrado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUser whereTipoArquivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrImportacaoUser whereUserId($value)
 * @mixin \Eloquent
 */
class FrImportacaoUser extends Model
{
    protected $table = 'fr_importacao_users';

    protected $fillable = [
            'arquivo',
            'user_id',
            'tipo_arquivo',
            'erros',
            'acertos',
            'qtd_certo',
            'qtd_errado',
            'nome_original',
        ];
}
