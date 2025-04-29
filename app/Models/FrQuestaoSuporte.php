<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrQuestaoSuporte
 *
 * @property int $id
 * @property string $titulo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $ordem
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoSuporte newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoSuporte newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoSuporte query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoSuporte whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoSuporte whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoSuporte whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoSuporte whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoSuporte whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoSuporte whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrQuestaoSuporte extends Model
{
    protected $table = 'fr_questao_suporte';

    protected $fillable = [
        'titulo',
        'ordem',
    ];
}
