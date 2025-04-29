<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrPlaylistAudioCast
 *
 * @property int $id
 * @property string $titulo
 * @property string|null $descricao
 * @property string|null $palavras_chave
 * @property string $capa
 * @property int $user_id
 * @property int $escola_id
 * @property int $instituicao_id
 * @property string $publicado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrAudioCast[] $audios
 * @property-read int|null $audios_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrAudioCast[] $lista
 * @property-read int|null $lista_count
 * @method static \Illuminate\Database\Eloquent\Builder|FrPlaylistAudioCast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrPlaylistAudioCast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrPlaylistAudioCast query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrPlaylistAudioCast whereCapa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPlaylistAudioCast whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPlaylistAudioCast whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPlaylistAudioCast whereEscolaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPlaylistAudioCast whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPlaylistAudioCast whereInstituicaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPlaylistAudioCast wherePalavrasChave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPlaylistAudioCast wherePublicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPlaylistAudioCast whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPlaylistAudioCast whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrPlaylistAudioCast whereUserId($value)
 * @mixin \Eloquent
 */
class FrPlaylistAudioCast extends Model
{
    protected $table = 'fr_playlist_audio_cast';

    protected $fillable = [
        'user_id',
        'escola_id',
        'instituicao_id',
        'titulo',
        'descricao',
        'palavras_chave',
        'capa',
        'publicado',
    ];


    public function audios()
    {
        return $this->belongsToMany(FrAudioCast::class,'fr_audio_playlist_audio_cast','playlist_id','audio_id')
            ->orderBy('ordem');
    }

    public function lista()
    {
        $cdn = config('app.cdn');
        $url = url('');
        return $this->belongsToMany(FrAudioCast::class,'fr_audio_playlist_audio_cast','playlist_id','audio_id')
            ->join('conteudos','conteudos.id','fr_audio_cast.conteudo_id')
            ->selectRaw("IF(conteudos.colecao_livro_id is null, CONCAT('".$cdn."/storage/cast/',conteudos.user_id,'/audio/',conteudos.conteudo), CONCAT('".$url."/public/',conteudos.conteudo)) as audio, IF(conteudos.colecao_livro_id is null, CONCAT('".$cdn."/storage/cast/',conteudos.user_id,'/capa/',conteudos.capa), CONCAT('".$cdn."/storage/capa_audios/',conteudos.capa)) as capa_audio,  conteudos.titulo, conteudos.conteudo")
            ->orderBy('ordem');
    }

}
