<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\InstituicaoTipo
 *
 * @property int $id
 * @property string $titulo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoTipo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoTipo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoTipo query()
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoTipo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoTipo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoTipo whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|InstituicaoTipo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class InstituicaoTipo extends Model
{
    protected $table = 'instituicao_tipo';

    protected $fillable = [
        'id',
        'titulo',
    ];

}
