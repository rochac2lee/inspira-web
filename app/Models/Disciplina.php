<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Disciplina
 *
 * @property int $id
 * @property string $titulo
 * @property string $descricao
 * @property string|null $sigla
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\CicloEtapa[] $ciclo_etapa
 * @property-read int|null $ciclo_etapa_count
 * @method static \Illuminate\Database\Eloquent\Builder|Disciplina newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Disciplina newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Disciplina query()
 * @method static \Illuminate\Database\Eloquent\Builder|Disciplina whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disciplina whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disciplina whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disciplina whereSigla($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disciplina whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Disciplina whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Disciplina extends Model
{
    #Model de disciplina
    protected $table = 'disciplinas';

    //Preenchiveis
    protected $fillable = [
        'titulo',
        'descricao',
    ];

    // Protegidos
    protected $hidden = [
    ];

    //Padrões
    protected $attributes = [
    ];

    #Relacionamentos

    #Relacionamento com a tabela de relacionamento com ciclo etapas
    public function ciclo_etapa()
    {
        return $this->belongsToMany('App\Models\CicloEtapa', 'disciplina_ciclo_etapas', 'disciplina_id', 'cicloetapa_id');
    }
}
