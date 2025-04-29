<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Aula
 *
 * @property int $id
 * @property int $curso_id
 * @property int $user_id
 * @property string $titulo
 * @property string $descricao
 * @property int|null $duracao
 * @property string|null $requisito
 * @property int|null $requisito_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $ordem
 * @method static \Illuminate\Database\Eloquent\Builder|Aula newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Aula newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Aula query()
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereCursoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereDuracao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereRequisito($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereRequisitoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Aula whereUserId($value)
 * @mixin \Eloquent
 */
class Aula extends Model
{
    //Preenchiveis
    protected $fillable = [
        'curso_id',
        'user_id',
        'titulo',
        'descricao',
        'duracao',
        'requisito',
        'requisito_id',
        'ordem'
    ];

    // Protegidos
    protected $hidden = [
    ];

    //Padrões
    protected $attributes = [
        'descricao' => '',
        'duracao' => 0,
        'requisito' => '',
        'requisito_id' => 0,
    ];

    public static function withConteudos($aula)
    {
        return ConteudoAula::
        with('conteudo')
        ->where([['curso_id', '=', $aula->curso_id], ['aula_id', '=', $aula->id]])
        ->whereHas('conteudo')
        ->get()
        ->sortBy('ordem');

        // return Conteudo::
        // with('conteudo_aula')
        // ->whereHas('conteudo_aula', function ($query) use ($aula) {
        //     $query->where([['curso_id', '=', $aula->curso_id], ['aula_id', '=', $aula->id]]);
        // })
        // ->get()
        // ->sortBy('conteudo_aula.ordem');
    }

    public function curso()
    {
        return $this->belongsTo('App\Curso', 'curso_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

}
