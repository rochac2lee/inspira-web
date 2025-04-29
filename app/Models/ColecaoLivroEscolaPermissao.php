<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoLivroEscolaPermissao
 *
 * @property int $colecao_id
 * @property int $escola_id
 * @property int $cicloetapa_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissao newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissao query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissao whereCicloetapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissao whereColecaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissao whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivroEscolaPermissao whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoLivroEscolaPermissao extends Model
{
    protected $table = 'colecao_livro_escola_permissao';

    //Preenchiveis
    protected $fillable = [
        'colecao_id',
        'escola_id',
        'cicloetapa_id',
    ];

}
