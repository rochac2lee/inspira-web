<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoLivroEscolaPermissaoPeriodo
 *
 * @property int $colecao_id
 * @property int $escola_id
 * @property string $periodo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissaoPeriodo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissaoPeriodo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissaoPeriodo query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissaoPeriodo whereColecaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissaoPeriodo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissaoPeriodo whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissaoPeriodo wherePeriodo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissaoPeriodo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoLivroEscolaPermissaoPeriodo extends Model
{
    protected $table = 'colecao_livro_escola_permissao_periodo';

    //Preenchiveis
    protected $fillable = [
        'colecao_id',
        'escola_id',
        'periodo',
    ];
}
