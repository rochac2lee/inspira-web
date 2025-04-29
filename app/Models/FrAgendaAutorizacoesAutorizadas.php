<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaAutorizacoesAutorizadas
 *
 * @property int $turma_id
 * @property int $aluno_id
 * @property int $autorizacao_id
 * @property int $responsavel_id
 * @property string $autorizado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $responsavel
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacoesAutorizadas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacoesAutorizadas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacoesAutorizadas query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacoesAutorizadas whereAlunoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacoesAutorizadas whereAutorizacaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacoesAutorizadas whereAutorizado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacoesAutorizadas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacoesAutorizadas whereResponsavelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacoesAutorizadas whereTurmaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacoesAutorizadas whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrAgendaAutorizacoesAutorizadas extends Model
{
    use HasFactory;

    protected $table = 'fr_agenda_autorizacao_alunos_autorizado';

    protected $fillable = [
        'turma_id',
        'aluno_id',
        'autorizacao_id',
        'responsavel_id',
        'autorizado',

    ];

    public function responsavel(){
        return $this->hasOne(User::class, 'id','responsavel_id');
    }
}
