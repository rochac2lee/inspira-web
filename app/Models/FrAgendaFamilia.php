<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaFamilia
 *
 * @property int $id
 * @property int $user_id
 * @property string $titulo
 * @property string $descricao
 * @property string|null $link_video
 * @property string $publicado
 * @property string $permissao_usuario
 * @property string|null $vizualizacao
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Escola[] $escolas
 * @property-read int|null $escolas_count
 * @property-read mixed $link_video_embed
 * @property-read mixed $link_video_iframe
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrAgendaFamiliaImagens[] $imagens
 * @property-read int|null $imagens_count
 * @property-read \App\Models\User|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamilia newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamilia newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamilia query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamilia whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamilia whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamilia whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamilia whereLinkVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamilia wherePermissaoUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamilia wherePublicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamilia whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamilia whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamilia whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaFamilia whereVizualizacao($value)
 * @mixin \Eloquent
 */
class FrAgendaFamilia extends Model
{
    protected $table = 'fr_agenda_espaco_familia';

    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'link_video',
        'publicado',
        'permissao_usuario',
        'vizualizacao',
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

    public function escolas()
    {
        return $this->belongsToMany(Escola::class,'fr_agenda_espaco_familia_escolas','familia_id','escola_id')
            ->withPivot(['escola_id','instituicao_id']);
    }

    public function imagens(){
        return $this->hasMany(FrAgendaFamiliaImagens::class, 'familia_id')
            ->orderByRaw('-ordem DESC')
            ->orderBy('id');
    }

    public function usuario(){
        return $this->hasOne(User::class, 'id','user_id');
    }
}
