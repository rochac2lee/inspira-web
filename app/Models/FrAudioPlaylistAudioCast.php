<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAudioPlaylistAudioCast
 *
 * @property int $playlist_id
 * @property int $audio_id
 * @property int $ordem
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioPlaylistAudioCast newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioPlaylistAudioCast newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioPlaylistAudioCast query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioPlaylistAudioCast whereAudioId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioPlaylistAudioCast whereOrdem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAudioPlaylistAudioCast wherePlaylistId($value)
 * @mixin \Eloquent
 */
class FrAudioPlaylistAudioCast extends Model
{
    protected $table = 'fr_audio_playlist_audio_cast';

    protected $fillable = [
        'playlist_id',
        'audio_id',
        'ordem',
    ];


}
