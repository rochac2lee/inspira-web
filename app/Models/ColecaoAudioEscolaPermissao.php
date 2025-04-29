<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoAudioEscolaPermissao
 *
 * @property int $colecao_id
 * @property int $escola_id
 * @property int $cicloetapa_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscolaPermissao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscolaPermissao newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscolaPermissao query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscolaPermissao whereCicloetapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscolaPermissao whereColecaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscolaPermissao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscolaPermissao whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioEscolaPermissao whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoAudioEscolaPermissao extends Model
{
    protected $table = 'colecao_audio_escola_permissao';

    //Preenchiveis
    protected $fillable = [
        'colecao_id',
        'escola_id',
        'cicloetapa_id',
        'todos',
    ];

}
