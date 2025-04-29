<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaRegistroRotinaOpet
 *
 * @property int $id
 * @property string $titulo
 * @property string $imagem
 * @property int|null $ordem
 * @property int $rotina
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $link_imagem
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotinaOpet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotinaOpet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotinaOpet query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotinaOpet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotinaOpet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotinaOpet whereImagem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotinaOpet whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotinaOpet whereRotina($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotinaOpet whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaRegistroRotinaOpet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrAgendaRegistroRotinaOpet extends Model
{
    protected $table = 'fr_agenda_registros_rotinas_opet';

    protected $fillable = [
        'titulo',
        'imagem',
        'ordem',
        'rotina',
    ];

    public function getLinkImagemAttribute()
    {
        if($this->imagem != ''){
            return config('app.cdn').'/storage/agenda/registro/rotinas/'.$this->imagem;
        }else{
            return null;
        }
    }


}
