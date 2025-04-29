<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaEnquete
 *
 * @property int $id
 * @property int $instituicao_id
 * @property int $escola_id
 * @property int $user_id
 * @property string $permissao_usuario
 * @property string $pergunta
 * @property string|null $alternativa_1
 * @property string|null $alternativa_2
 * @property string|null $alternativa_3
 * @property string|null $alternativa_4
 * @property string|null $alternativa_5
 * @property string|null $alternativa_6
 * @property string|null $alternativa_7
 * @property int $qtd_alternativa
 * @property string $imagem
 * @property string $publicado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $alunos
 * @property-read int|null $alunos_count
 * @property-read mixed $link_imagem
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrAgendaEnqueteRespostas[] $respondidos
 * @property-read int|null $respondidos_count
 * @property-read \App\Models\User|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereAlternativa1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereAlternativa2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereAlternativa3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereAlternativa4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereAlternativa5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereAlternativa6($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereAlternativa7($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereImagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete wherePergunta($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete wherePermissaoUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete wherePublicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereQtdAlternativa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaEnquete whereUserId($value)
 * @mixin \Eloquent
 */
class FrAgendaEnquete extends Model
{
    protected $table = 'fr_agenda_enquetes';

    protected $fillable = [
        'user_id',
        'instituicao_id',
        'escola_id',
        'permissao_usuario',
        'pergunta',
        'alternativa_1',
        'alternativa_2',
        'alternativa_3',
        'alternativa_4',
        'alternativa_5',
        'alternativa_6',
        'alternativa_7',
        'qtd_alternativa',
        'imagem',
        'publicado',
    ];

    public function getLinkImagemAttribute()
    {
        if($this->imagem != '') {
            return config('app.cdn') . '/storage/agenda/enquetes/' . $this->user_id . '/' . $this->id . '/' . $this->imagem;
        }
        else{
            return null;
        }
    }

    public function usuario(){
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function alunos()
    {
        return $this->belongsToMany(User::class,'fr_agenda_enquetes_turmas','enquete_id','aluno_id')->orderBy('nome_completo')
            ->withPivot(['aluno_id','turma_id','escola_id','instituicao_id']);
    }

    public function respondidos()
    {
        return $this->hasMany(FrAgendaEnqueteRespostas::class, 'enquete_id','id');
    }
}
