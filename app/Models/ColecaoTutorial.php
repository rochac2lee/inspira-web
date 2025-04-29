<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoTutorial
 *
 * @property int $id
 * @property string $nome
 * @property string|null $selo
 * @property string|null $img
 * @property int|null $ordem
 * @property string $eh_professor
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoTutorial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoTutorial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoTutorial query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoTutorial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoTutorial whereEhProfessor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoTutorial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoTutorial whereImg($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoTutorial whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoTutorial whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoTutorial whereSelo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoTutorial whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoTutorial extends Model
{
    protected $table = 'colecao_tutorial';

    protected $fillable = ['id', 'nome', 'selo', 'img','eh_professor'];
}
