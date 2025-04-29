<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Tutorial
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $capa
 * @property string $titulo
 * @property string $descricao
 * @property string $descricao_modal
 * @property string $codigo_vimeo
 * @property string $permissao
 * @property int $ordem
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int|null $colecao_id
 * @property string|null $arquivo_pdf
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial newQuery()
 * @method static \Illuminate\Database\Query\Builder|Tutorial onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial whereArquivoPdf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial whereCapa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial whereCodigoVimeo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial whereColecaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial whereDescricaoModal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial wherePermissao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tutorial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Tutorial withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Tutorial withoutTrashed()
 * @mixin \Eloquent
 */
class Tutorial extends Model
{
	use SoftDeletes;
	protected $table = 'tutorial';

    protected $fillable = [
        'id',
        'capa',
        'titulo',
        'descricao',
        'descricao_modal',
        'codigo_vimeo',
        'permissao',
        'instituicao_tipo_id',
        'ordem',
    ];
}
