<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoEdInfantil
 *
 * @property int $id
 * @property string $nome
 * @property string|null $selo
 * @property string|null $img
 * @property int|null $ordem
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $eh_professor
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoEdInfantil newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoEdInfantil newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoEdInfantil query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoEdInfantil whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoEdInfantil whereEhProfessor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoEdInfantil whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoEdInfantil whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoEdInfantil whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoEdInfantil whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoEdInfantil whereSelo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoEdInfantil whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoEdInfantil extends Model
{
   protected $table = 'colecao_ed_infantil';

   protected $fillable = ['id', 'nome', 'selo', 'img','eh_professor'];
}
