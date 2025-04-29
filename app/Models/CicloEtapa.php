<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CicloEtapa
 *
 * @property int $id
 * @property int $ciclo_id
 * @property string $titulo
 * @property string $descricao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ciclo $ciclo
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Disciplina[] $disciplina
 * @property-read int|null $disciplina_count
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapa query()
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapa whereCicloId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapa whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapa whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapa whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CicloEtapa whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CicloEtapa extends Model
{
    #Model de etapa de cilo de ensino do aluno
    protected $table = 'ciclo_etapas';

    //Preenchiveis
    protected $fillable = [
        'ciclo_id',
        'titulo',
        'descricao',
    ];

     // Protegidos
     protected $hidden = [
         'id'
     ];

     //Padrões
     protected $attributes = [
     ];

    #Relacionamentos

     //Relacionamento de ciclos
    public function ciclo(){
        return $this->belongsTo(Ciclo::class);
    }

    //Relacionamento de disciplina
    public function disciplina()
    {
        return $this->belongsToMany(Disciplina::class, 'disciplina_ciclo_etapas', 'cicloetapa_id', 'disciplina_id');
    }
}
