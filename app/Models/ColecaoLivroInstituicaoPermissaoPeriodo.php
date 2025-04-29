<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoLivroInstituicaoPermissaoPeriodo
 *
 * @property int $colecao_id
 * @property int $instituicao_id
 * @property string $periodo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicaoPermissaoPeriodo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicaoPermissaoPeriodo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicaoPermissaoPeriodo query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicaoPermissaoPeriodo whereColecaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicaoPermissaoPeriodo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicaoPermissaoPeriodo whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicaoPermissaoPeriodo wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroInstituicaoPermissaoPeriodo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoLivroInstituicaoPermissaoPeriodo extends Model
{
    protected $table = 'colecao_livro_instituicao_permissao_periodo';

    //Preenchiveis
    protected $fillable = [
        'colecao_id',
        'instituicao_id',
        'periodo',
    ];
}
