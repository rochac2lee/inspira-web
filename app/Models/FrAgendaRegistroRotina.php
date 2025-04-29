<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\FrAgendaRegistroRotina
 *
 * @property int $id
 * @property int $instituicao_id
 * @property string $titulo
 * @property string $imagem
 * @property int|null $ordem
 * @property int $rotina
 * @property string $ativo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read mixed $link_imagem
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotina newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotina newQuery()
 * @method static \Illuminate\Database\Query\Builder|FrAgendaRegistroRotina onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotina query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotina whereAtivo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotina whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotina whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotina whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotina whereImagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotina whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotina whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotina whereRotina($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotina whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotina whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|FrAgendaRegistroRotina withTrashed()
 * @method static \Illuminate\Database\Query\Builder|FrAgendaRegistroRotina withoutTrashed()
 * @mixin \Eloquent
 */
class FrAgendaRegistroRotina extends Model
{
    use SoftDeletes;
    protected $table = 'fr_agenda_registros_rotinas';

    protected $fillable = [
        'instituicao_id',
        'titulo',
        'imagem',
        'ordem',
        'rotina',
        'ativo',
    ];

    public function getLinkImagemAttribute()
    {
        if($this->imagem != ''){
            return config('app.cdn').'/storage/agenda/registro/rotinas/'.$this->instituicao_id.'/'.$this->imagem;
        }else{
            return null;
        }
    }


}
