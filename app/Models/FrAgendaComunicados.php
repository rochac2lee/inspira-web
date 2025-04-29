<?php

namespace App\Models;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\FrAgendaComunicados
 *
 * @property int $id
 * @property int $user_id
 * @property string $titulo
 * @property string $descricao
 * @property string|null $link_video
 * @property string $publicado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $permissao_usuario
 * @property-read mixed $link_video_embed
 * @property-read mixed $link_video_iframe
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrAgendaComunicadoImagens[] $imagens
 * @property-read int|null $imagens_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FrTurma[] $turmas
 * @property-read int|null $turmas_count
 * @property-read \App\Models\User|null $usuario
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicados newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicados newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicados query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicados whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicados whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicados whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicados whereLinkVideo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicados wherePermissaoUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicados wherePublicado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicados whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicados whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrAgendaComunicados whereUserId($value)
 * @mixin \Eloquent
 */
class FrAgendaComunicados extends Model
{
    protected $table = 'fr_agenda_comunicados';

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
        return $this->belongsToMany(FrTurma::class,'fr_agenda_comunicados_fr_turmas','agenda_comunicado_id','turma_id')
            ->withPivot(['escola_id','instituicao_id']);
    }

    public function escolas()
    {
        return $this->hasMany(FrAgendaComunicadosTurmas::class,'agenda_comunicado_id')
            ->join('escolas', 'fr_agenda_comunicados_fr_turmas.escola_id', 'escolas.id')
            ->selectRaw('escolas.titulo, escolas.id, fr_agenda_comunicados_fr_turmas.agenda_comunicado_id')
            ->groupBy('fr_agenda_comunicados_fr_turmas.agenda_comunicado_id')
            ->groupBy('escolas.id');
    }

    public function imagens(){
        return $this->hasMany(FrAgendaComunicadoImagens::class, 'comunicado_id')
            ->orderByRaw('-ordem DESC')
            ->orderBy('id');
    }

    public function usuario(){
        return $this->hasOne(User::class, 'id','user_id');
    }
}
