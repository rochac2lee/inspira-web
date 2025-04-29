<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Ciclo
 *
 * @property int $id
 * @property string $titulo
 * @property string $descricao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CicloEtapa[] $cicloEtapas
 * @property-read int|null $ciclo_etapas_count
 * @method static \Illuminate\Database\Eloquent\Builder|Ciclo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ciclo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ciclo query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ciclo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ciclo whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ciclo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ciclo whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ciclo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Ciclo extends Model
{
    #Model de ciclo de ensino do aluno
    protected $table = 'ciclos';

       //Preenchiveis
       protected $fillable = [
        'titulo',
        'descricao',
    ];

    public function cicloEtapas(){
        return $this->hasMany(CicloEtapa::class);
    }

}
