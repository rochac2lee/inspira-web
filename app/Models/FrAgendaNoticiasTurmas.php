<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaNoticiasTurmas
 *
 * @property int $turma_id
 * @property int $agenda_noticia_id
 * @property int $instituicao_id
 * @property int $escola_id
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiasTurmas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiasTurmas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiasTurmas query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiasTurmas whereAgendaNoticiaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiasTurmas whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiasTurmas whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiasTurmas whereTurmaId($value)
 * @mixin \Eloquent
 */
class FrAgendaNoticiasTurmas extends Model
{
    protected $table = 'fr_agenda_noticias_turmas';

    protected $fillable = [
        'instituicao_id',
        'escola_id',
        'turma_id',
        'agenda_comunicado_id',
    ];
}
