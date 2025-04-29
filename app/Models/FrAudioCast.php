<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAudioCast
 *
 * @property int $id
 * @property int $conteudo_id
 * @property int $user_id
 * @property int $escola_id
 * @property int $instituicao_id
 * @property string $eh_dono
 * @property string $publicado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Conteudo|null $conteudo
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioCast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioCast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioCast query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioCast whereConteudoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioCast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioCast whereEhDono($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioCast whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioCast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioCast whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioCast wherePublicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioCast whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioCast whereUserId($value)
 * @mixin \Eloquent
 */
class FrAudioCast extends Model
{
    protected $table = 'fr_audio_cast';

    protected $fillable = [
        'conteudo_id',
        'user_id',
        'escola_id',
        'instituicao_id',
        'eh_dono',
        'publicado',
    ];

    public function conteudo()
    {
        return $this->hasOne('App\Models\Conteudo','id','conteudo_id');
    }
}
