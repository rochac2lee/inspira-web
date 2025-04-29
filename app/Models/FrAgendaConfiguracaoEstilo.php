<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\FrAgendaConfiguracaoEstilo
 *
 * @property int $id
 * @property int $user_id
 * @property int $instituicao_id
 * @property string|null $titulo_inicial
 * @property string|null $cor_primaria
 * @property string|null $imagem
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $link_imagem
 * @property-read \App\Models\User|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoEstilo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoEstilo newQuery()
 * @method static \Illuminate\Database\Query\Builder|FrAgendaConfiguracaoEstilo onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoEstilo query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoEstilo whereCorPrimaria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoEstilo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoEstilo whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoEstilo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoEstilo whereImagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoEstilo whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoEstilo whereTituloInicial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoEstilo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoEstilo whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|FrAgendaConfiguracaoEstilo withTrashed()
 * @method static \Illuminate\Database\Query\Builder|FrAgendaConfiguracaoEstilo withoutTrashed()
 * @mixin \Eloquent
 */
class FrAgendaConfiguracaoEstilo extends Model
{
    use SoftDeletes;
    protected $table = 'fr_agenda_configuracao_estilos';

    protected $fillable = [
        'user_id',
        'instituicao_id',
        'titulo_inicial',
        'cor_primaria',
        'imagem',
    ];

    public function getLinkImagemAttribute()
    {
        if($this->imagem == ''){
            return null;
        }else {
            return config('app.cdn') . '/storage/agenda/configuracoes/estilo/' . $this->instituicao_id . '/imagem_inicio/' . $this->imagem;
        }
    }

    public function usuario(){
        return $this->hasOne(User::class, 'id','user_id');
    }
}
