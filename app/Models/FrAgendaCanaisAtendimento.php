<?php

namespace App\Models;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaCanaisAtendimento
 *
 * @property int $id
 * @property int $user_id
 * @property int $instituicao_id
 * @property string $nome
 * @property string $cargo
 * @property string $imagem
 * @property string $email
 * @property string $telefone
 * @property string $telefone_eh_zap
 * @property string $publicado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Escola[] $escolas
 * @property-read int|null $escolas_count
 * @property-read mixed $link_imagem
 * @property-read \App\Models\User|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento whereCargo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento whereImagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento wherePublicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento whereTelefone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento whereTelefoneEhZap($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCanaisAtendimento whereUserId($value)
 * @mixin \Eloquent
 */
class FrAgendaCanaisAtendimento extends Model
{
    protected $table = 'fr_agenda_canais_atendimentos';

    protected $fillable = [
        'user_id',
        'instituicao_id',
        'nome',
        'cargo',
        'imagem',
        'email',
        'telefone',
        'telefone_eh_zap',
        'publicado',
    ];

    public function getLinkImagemAttribute()
    {
        return config('app.cdn').'/storage/agenda/canais_atendimento/'.$this->user_id.'/'.$this->id.'/'.$this->imagem;
    }

    public function escolas()
    {
        return $this->belongsToMany(Escola::class,'fr_agenda_canais_atendimento_escolas','canal_id','escola_id')
            ->withPivot(['escola_id','instituicao_id']);
    }

    public function usuario(){
        return $this->hasOne(User::class, 'id','user_id');
    }
}
