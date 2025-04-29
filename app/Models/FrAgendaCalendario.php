<?php

namespace App\Models;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaCalendario
 *
 * @property int $id
 * @property int $user_id
 * @property string $titulo
 * @property string $descricao
 * @property \Illuminate\Support\Carbon $data_inicial
 * @property \Illuminate\Support\Carbon $data_final
 * @property string $dia_inteiro
 * @property string|null $tema
 * @property string $permissao_usuario
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Escola[] $escolas
 * @property-read int|null $escolas_count
 * @property-read \App\Models\User|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCalendario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCalendario newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCalendario query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCalendario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCalendario whereDataFinal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCalendario whereDataInicial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCalendario whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCalendario whereDiaInteiro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCalendario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCalendario wherePermissaoUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCalendario whereTema($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCalendario whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCalendario whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaCalendario whereUserId($value)
 * @mixin \Eloquent
 */
class FrAgendaCalendario extends Model
{
    protected $table = 'fr_agenda_calendario';
    protected $dates = ['data_inicial', 'data_final'];

    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'data_inicial',
        'data_final',
        'dia_inteiro',
        'tema',
        'permissao_usuario',
    ];

    public function usuario(){
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function escolas()
    {
        return $this->belongsToMany(Escola::class,'fr_agenda_calendario_escola','calendario_id','escola_id')
            ->withPivot(['instituicao_id']);
    }

}
