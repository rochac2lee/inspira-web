<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\FrAgendaConfiguracaoRotulosCalendario
 *
 * @property int $id
 * @property int $user_id
 * @property int $instituicao_id
 * @property string|null $titulo
 * @property string|null $cor
 * @property int|null $ordem
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoRotulosCalendario newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoRotulosCalendario newQuery()
 * @method static \Illuminate\Database\Query\Builder|FrAgendaConfiguracaoRotulosCalendario onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoRotulosCalendario query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoRotulosCalendario whereCor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoRotulosCalendario whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoRotulosCalendario whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoRotulosCalendario whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoRotulosCalendario whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoRotulosCalendario whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoRotulosCalendario whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoRotulosCalendario whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaConfiguracaoRotulosCalendario whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|FrAgendaConfiguracaoRotulosCalendario withTrashed()
 * @method static \Illuminate\Database\Query\Builder|FrAgendaConfiguracaoRotulosCalendario withoutTrashed()
 * @mixin \Eloquent
 */
class FrAgendaConfiguracaoRotulosCalendario extends Model
{
    use SoftDeletes;
    protected $table = 'fr_agenda_configuracao_rotulos_calendario';

    protected $fillable = [
        'user_id',
        'instituicao_id',
        'titulo',
        'cor',
        'ordem',
    ];

    public function usuario(){
        return $this->hasOne(User::class, 'id','user_id');
    }
}
