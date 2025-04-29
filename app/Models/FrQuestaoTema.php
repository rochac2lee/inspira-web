<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrQuestaoTema
 *
 * @property int $id
 * @property string $titulo
 * @property int $disciplina_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoTema newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoTema newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoTema query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoTema whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoTema whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoTema whereDisciplinaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoTema whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoTema whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoTema whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrQuestaoTema extends Model
{
    protected $table = 'fr_questao_tema';

    protected $fillable = [
        'titulo',
    ];
}
