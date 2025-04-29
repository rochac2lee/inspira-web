<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Instituicao
 *
 * @property int $id
 * @property int $user_id
 * @property string $titulo
 * @property string|null $descricao
 * @property string|null $template_id
 * @property string|null $logo_url
 * @property string|null $style_url
 * @property int|null $pagamento_tipo_id
 * @property int|null $instituicao_tipo_id
 * @property string|null $termos_uso
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $embedded
 * @property int $status_id
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $cor_primaria
 * @property string|null $cor_secundaria
 * @property string $permissao_ead
 * @property string $permissao_indica
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Escola[] $escolas
 * @property-read int|null $escolas_count
 * @property-read \App\Models\FrAgendaConfiguracaoEstilo|null $estiloAgenda
 * @property-read \App\Models\InstituicaoTipo|null $tipo_instituicao
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao newQuery()
 * @method static \Illuminate\Database\Query\Builder|Instituicao onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao query()
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereCorPrimaria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereCorSecundaria($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereEmbedded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereInstituicaoTipoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereLogoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao wherePagamentoTipoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao wherePermissaoEad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao wherePermissaoIndica($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereStyleUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereTemplateId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereTermosUso($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Instituicao whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Instituicao withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Instituicao withoutTrashed()
 * @mixin \Eloquent
 */
class Instituicao extends Model
{
    use SoftDeletes;

    protected $table = 'instituicao';

    //Preenchiveis
    protected $fillable = [
        'user_id',
        'titulo',
        'descricao',
        'template_id',
        'logo_url',
        'style_url',
        'pagamento_tipo_id',
        'instituicao_tipo_id',
        'termos_uso',
        'embedded',
        'cor_primaria',
        'cor_secundaria',
        'status_id',
        'estrutura_trimestral',
        
    ];
    public function tipo_instituicao()
    {
        return $this->hasOne('App\Models\InstituicaoTipo','id','instituicao_tipo_id');
    }

    public function escolas()
    {
        return $this->hasMany(Escola::class,'instituicao_id','id');
    }

    public function estiloAgenda()
    {
        return $this->hasOne(FrAgendaConfiguracaoEstilo::class);
    }
}
