<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaFamiliaImagens
 *
 * @property int $id
 * @property int $familia_id
 * @property string $caminho
 * @property int|null $ordem
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $link
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamiliaImagens newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamiliaImagens newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamiliaImagens query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamiliaImagens whereCaminho($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamiliaImagens whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamiliaImagens whereFamiliaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamiliaImagens whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamiliaImagens whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamiliaImagens whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class FrAgendaFamiliaImagens extends Model
{

    protected $table = 'fr_agenda_espaco_familia_imagens';

    protected $fillable = [
        'familia_id',
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
        return config('app.cdn').'/storage/agenda/familia/'.$this->userId.'/'.$this->comunicadoId.'/'.$this->caminho;
    }
}
