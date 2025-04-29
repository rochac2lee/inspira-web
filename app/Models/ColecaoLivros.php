<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoLivros
 *
 * @property int $id
 * @property string $nome
 * @property string $selo
 * @property string $img
 * @property int|null $tipo
 * @property int|null $ordem
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $aluno
 * @property string|null $professor
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivros newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivros newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivros query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivros whereAluno($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivros whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivros whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivros whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivros whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivros whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivros whereProfessor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivros whereSelo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivros whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoLivros whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoLivros extends Model
{
    protected $table = 'colecao_livros';

    protected $fillable = ['id', 'nome', 'selo', 'img','ordem'];
}
