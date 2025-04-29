<?php

namespace App\Models;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaNoticias
 *
 * @property int $id
 * @property int $user_id
 * @property string $titulo
 * @property string $descricao
 * @property string|null $link_video
 * @property string $publicado
 * @property string $permissao_usuario
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $link_video_embed
 * @property-read mixed $link_video_iframe
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrAgendaNoticiaImagens[] $imagens
 * @property-read int|null $imagens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrTurma[] $turmas
 * @property-read int|null $turmas_count
 * @property-read \App\Models\User|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticias newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticias newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticias query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticias whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticias whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticias whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticias whereLinkVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticias wherePermissaoUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticias wherePublicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticias whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticias whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaNoticias whereUserId($value)
 * @mixin \Eloquent
 */
class FrAgendaNoticias extends Model
{
    protected $table = 'fr_agenda_noticias';

    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'link_video',
        'publicado',
        'permissao_usuario',
    ];

    public function getLinkVideoEmbedAttribute()
    {
        return transformaUrlVideoEmbed($this->link_video);
    }

    public function getLinkVideoIframeAttribute()
    {
        $link = $this->getLinkVideoEmbedAttribute();
        return trasnformaVideoIframe($link);
    }

    public function turmas()
    {
        return $this->belongsToMany(FrTurma::class,'fr_agenda_noticias_turmas','agenda_noticia_id','turma_id')
            ->withPivot(['escola_id','instituicao_id']);
    }

    public function imagens(){
        return $this->hasMany(FrAgendaNoticiaImagens::class, 'noticia_id')
            ->orderByRaw('-ordem DESC')
            ->orderBy('id');
    }

    public function usuario(){
        return $this->hasOne(User::class, 'id','user_id');
    }
}
