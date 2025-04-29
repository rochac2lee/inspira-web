<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrBncc
 *
 * @property int $id
 * @property int $disciplina_id
 * @property int $cicloetapa_id
 * @property string|null $unidade_tematica
 * @property string $objeto_conhecimento
 * @property string $habilidade
 * @property string $codigo_habilidade
 * @property string|null $comentario
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|FrBncc newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrBncc newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrBncc query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrBncc whereCicloetapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrBncc whereCodigoHabilidade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrBncc whereComentario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrBncc whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrBncc whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrBncc whereDisciplinaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrBncc whereHabilidade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrBncc whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrBncc whereObjetoConhecimento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrBncc whereUnidadeTematica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrBncc whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrBncc extends Model
{
    use SoftDeletes;
    protected $table = 'fr_bncc';
    
    protected $fillable = [
        'disciplina_id',
        'cicloetapa_id',
        'unidade_tematica',
        'objeto_conhecimento',
        'habilidade',
        'codigo_habilidade',
        'comentario',
    ];
}
