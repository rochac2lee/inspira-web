<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ConsumoConteudo
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $curso_id
 * @property int|null $aula_id
 * @property int $conteudo_id
 * @property float|null $consumo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ConsumoConteudo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConsumoConteudo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConsumoConteudo query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConsumoConteudo whereAulaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsumoConteudo whereConsumo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsumoConteudo whereConteudoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsumoConteudo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsumoConteudo whereCursoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsumoConteudo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsumoConteudo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConsumoConteudo whereUserId($value)
 * @mixin \Eloquent
 */
class ConsumoConteudo extends Model
{
    protected $fillable = [
        'user_id',
        'curso_id',
        'aula_id',
        'conteudo_id',
        'consumo',
    ];

    // Consumo is the content file size in kb (1000KB = 1MB)

    protected $hidden = [
    ];

    protected $attributes = [
        'consumo' => 0,
    ];

    public function curso()
    {
        return $this->belongsTo('App\Curso', 'curso_id');
    }

    public function aula()
    {
        return $this->belongsTo('App\Conteudo', 'aula_id');
    }

    public function conteudo()
    {
        return $this->belongsTo('App\Conteudo', 'conteudo_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public static function drawBarraConsumoEscola()
    {
        $escola = \App\Escola::where('user_id', '=', \Auth::user()->id)->first();

        if($escola == null || \Auth::user()->permissao != "Z")
        {
            return '';
        }

        $consumoAtual = \App\ConsumoConteudo::
        whereHas('curso', function($query) use ($escola) {
            $query->where('user_id', \Auth::user()->id)
            ->orWhere('escola_id', $escola->id);
        })
        ->orWhereHas('aula', function($query) {
            $query->where('user_id', \Auth::user()->id);
        })
        ->orWhereHas('conteudo', function($query) {
            $query->where('user_id', \Auth::user()->id);
        })
        ->whereMonth('created_at', '=', date("m"))
        ->sum('consumo') / 1000000;

        if(\App\Licenca::where([['user_id', '=', \Auth::user()->id]])->first() == null)
        {
            $bandaDisponivel = 0.05;
        }
        else
        {
            $bandaDisponivel  = \App\Licenca::where([['user_id', '=', \Auth::user()->id]])->first()->armazenamento;
        }

        $percentualConsumo = 0;

        if($bandaDisponivel > 0)
        {
            $percentualConsumo = ($consumoAtual * 100) / $bandaDisponivel;
        }

        $percentualRestante = 100 - $percentualConsumo;

        $barraConsumo = "
        <div class='list-group-item small border-0 consumo-conteudo'>
            <p class='font-weight-normal text-transform-none mb-0'>Consumo de banda (Mês atual)</p>
            <p class='font-weight-normal text-transform-none'>" . number_format($consumoAtual, 2, ",", ".") . " GB /" . number_format($bandaDisponivel, 2, ",", ".") . " GB</p>
            <div class='progress'>
                <div class='progress-bar progress-bar-striped bg-warning' role='progressbar' style='width: $percentualConsumo%' aria-valuenow='$percentualConsumo' aria-valuemin='0' aria-valuemax='100'>" . number_format($percentualConsumo, 2, ",", ".") . "%</div>
                <div class='progress-bar progress-bar-striped bg-success progress-bar-animated' role='progressbar' style='width: $percentualRestante%' aria-valuenow='$percentualRestante' aria-valuemin='0' aria-valuemax='100'>" . number_format($percentualRestante, 2, ",", ".") . "%</div>

            </div>
        </div>
        ";

        return $barraConsumo;
    }
}
