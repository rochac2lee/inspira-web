<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaNoticiaImagens
 *
 * @property int $id
 * @property int $noticia_id
 * @property string $caminho
 * @property int|null $ordem
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $link
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiaImagens newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiaImagens newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiaImagens query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiaImagens whereCaminho($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiaImagens whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiaImagens whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiaImagens whereNoticiaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiaImagens whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticiaImagens whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrAgendaNoticiaImagens extends Model
{

    protected $table = 'fr_agenda_noticias_imagens';

    protected $fillable = [
        'comunicado_id',
        'caminho',
        'ordem',
    ];

    protected $userId;
    protected $comunicadoId;

    public function setUserId($value)
    {
        return $this->userId = $value;
    }

    public function setComunicadoId($value)
    {
        return $this->comunicadoId = $value;
    }

    public function getLinkAttribute()
    {
        return config('app.cdn').'/storage/agenda/noticias/'.$this->userId.'/'.$this->comunicadoId.'/'.$this->caminho;
    }
}
