<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrQuestaoFormato
 *
 * @property int $id
 * @property string $titulo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property int|null $ordem
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoFormato newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoFormato newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoFormato query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoFormato whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoFormato whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoFormato whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoFormato whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoFormato whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestaoFormato whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrQuestaoFormato extends Model
{
    protected $table = 'fr_questao_formato';

    protected $fillable = [
        'titulo',
        'ordem',
    ];
}
