<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaComunicadosTurmas
 *
 * @property int $turma_id
 * @property int $agenda_comunicado_id
 * @property int $instituicao_id
 * @property int $escola_id
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadosTurmas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadosTurmas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadosTurmas query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadosTurmas whereAgendaComunicadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadosTurmas whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadosTurmas whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadosTurmas whereTurmaId($value)
 * @mixin \Eloquent
 */
class FrAgendaComunicadosTurmas extends Model
{
    protected $table = 'fr_agenda_comunicados_fr_turmas';

    protected $fillable = [
        'instituicao_id',
        'escola_id',
        'turma_id',
        'agenda_comunicado_id',
    ];
}
