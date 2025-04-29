<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaComunicadoImagens
 *
 * @property int $id
 * @property int $comunicado_id
 * @property string $caminho
 * @property int|null $ordem
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $link
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadoImagens newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadoImagens newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadoImagens query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadoImagens whereCaminho($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadoImagens whereComunicadoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadoImagens whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadoImagens whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadoImagens whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicadoImagens whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrAgendaComunicadoImagens extends Model
{

    protected $table = 'fr_agenda_comunicado_imagens';

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
        return config('app.cdn').'/storage/agenda/comunicados/'.$this->userId.'/'.$this->comunicadoId.'/'.$this->caminho;
    }
}
