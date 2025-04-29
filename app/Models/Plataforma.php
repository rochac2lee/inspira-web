<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;

/**
 * App\Models\Plataforma
 *
 * @property int $id
 * @property string|null $politica
 * @property string|null $termo
 * @property string|null $capa
 * @property string|null $template_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Plataforma newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plataforma newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Plataforma query()
 * @method static \Illuminate\Database\Eloquent\Builder|Plataforma whereCapa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plataforma whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plataforma whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plataforma wherePolitica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plataforma whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plataforma whereTermo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Plataforma whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Plataforma extends Model
{
    protected $table = 'plataforma';

    //Preenchiveis
    protected $fillable = [
        'politica',
        'termo',
    ];

}
