<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaEnqueteRespostas
 *
 * @property int $turma_id
 * @property int $aluno_id
 * @property int $enquete_id
 * @property int $responsavel_id
 * @property string $resposta
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $responsavel
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteRespostas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteRespostas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteRespostas query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteRespostas whereAlunoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteRespostas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteRespostas whereEnqueteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteRespostas whereResponsavelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteRespostas whereResposta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteRespostas whereTurmaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnqueteRespostas whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrAgendaEnqueteRespostas extends Model
{
    use HasFactory;

    protected $table = 'fr_agenda_enquetes_respostas';

    protected $fillable = [
        'turma_id',
        'aluno_id',
        'enquete_id',
        'responsavel_id',
        'resposta',

    ];

    public function responsavel(){
        return $this->hasOne(User::class, 'id','responsavel_id');
    }
}
