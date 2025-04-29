<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaAutorizacao
 *
 * @property int $id
 * @property int $user_id
 * @property string $titulo
 * @property string $descricao
 * @property string|null $imagem
 * @property string $permissao_usuario
 * @property int $instituicao_id
 * @property int $escola_id
 * @property string $publicado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $alunos
 * @property-read int|null $alunos_count
 * @property-read \App\Models\Escola|null $escola
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrAgendaAutorizacoesAutorizadas[] $respondidos
 * @property-read int|null $respondidos_count
 * @property-read \App\Models\User|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacao newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacao query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacao whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacao whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacao whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacao whereImagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacao whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacao wherePermissaoUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacao wherePublicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacao whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacao whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaAutorizacao whereUserId($value)
 * @mixin \Eloquent
 */
class FrAgendaAutorizacao extends Model
{
    protected $table = 'fr_agenda_autorizacao';

    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'permissao_usuario',
        'instituicao_id',
        'escola_id',
        'publicado',
        'imagem',
    ];

    public function getLinkImagemAttribute()
    {
        if($this->imagem == '') {
            return null;
        }else{
            return config('app.cdn').'/storage/agenda/autorizacao/'.$this->user_id.'/'.$this->id.'/'.$this->imagem;
        }
    }

    public function escola()
    {
        return $this->hasOne(Escola::class,'id','escola_id');
    }

    public function alunos()
    {
        return $this->belongsToMany(User::class,'fr_agenda_autorizacao_alunos','autorizacao_id','aluno_id')->orderBy('nome_completo')
            ->withPivot(['aluno_id','turma_id','escola_id','instituicao_id']);
    }

    public function respondidos()
    {
        return $this->hasMany(FrAgendaAutorizacoesAutorizadas::class, 'autorizacao_id','id');
    }

    public function usuario(){
        return $this->hasOne(User::class, 'id','user_id');
    }
}
