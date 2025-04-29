<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ColecaoAudioInstituicaoPermissao
 *
 * @property int $colecao_id
 * @property int $instituicao_id
 * @property int $cicloetapa_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicaoPermissao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicaoPermissao newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicaoPermissao query()
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicaoPermissao whereCicloetapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicaoPermissao whereColecaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicaoPermissao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicaoPermissao whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ColecaoAudioInstituicaoPermissao whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ColecaoAudioInstituicaoPermissao extends Model
{
    protected $table = 'colecao_audio_instituicao_permissao';

    //Preenchiveis
    protected $fillable = [
        'colecao_id',
        'instituicao_id',
        'cicloetapa_id',
        'todos',
    ];

}
