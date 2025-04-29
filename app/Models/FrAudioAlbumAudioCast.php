<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAudioAlbumAudioCast
 *
 * @property int $album_id
 * @property int $audio_id
 * @property int $ordem
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioAlbumAudioCast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioAlbumAudioCast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioAlbumAudioCast query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioAlbumAudioCast whereAlbumId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioAlbumAudioCast whereAudioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioAlbumAudioCast whereOrdem($value)
 * @mixin \Eloquent
 */
class FrAudioAlbumAudioCast extends Model
{
    protected $table = 'fr_audio_album_audio_cast';

    protected $fillable = [
        'album_id',
        'audio_id',
        'ordem',
    ];


}
