<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaEnqueteTurmas
 *
 * @property int $turma_id
 * @property int $aluno_id
 * @property int $enquete_id
 * @property int $instituicao_id
 * @property int $escola_id
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteTurmas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteTurmas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteTurmas query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteTurmas whereAlunoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteTurmas whereEnqueteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteTurmas whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteTurmas whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteTurmas whereTurmaId($value)
 * @mixin \Eloquent
 */
class FrAgendaEnqueteTurmas extends Model
{
    protected $table = 'fr_agenda_enquetes_turmas';

    protected $fillable = [
        'instituicao_id',
        'escola_id',
        'turma_id',
        'enquete_id',
    ];
}
