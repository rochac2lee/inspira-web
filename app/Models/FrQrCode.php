<?php

namespace App\Models;

use App\Models\CicloEtapa;
use App\Models\ColecaoLivro;
use App\Models\Disciplina;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * App\Models\FrQrCode
 *
 * @property int $id
 * @property int $user_id
 * @property string $url
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $cicloetapa_id
 * @property string $chave
 * @property-read \App\Models\CicloEtapa|null $cicloEtapa
 * @method static \Illuminate\Database\Eloquent\Builder|FrQrCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQrCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQrCode query()
 * @method static \Illuminate\Database\Eloquent\Builder|FrQrCode whereChave($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQrCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQrCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQrCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQrCode whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|FrQuestao whereCicloetapaId($value)
 * @mixin \Eloquent
 */
class FrQrCode extends Model
{
    use SoftDeletes;

    protected $table = 'fr_qrcode';

    protected $fillable = [
        'url',
        'chave',
        'descricao',
        'observacao',
        'tipo_midia',
        'disciplina_id',
        'colecao_livro_id',
        'ciclo_etapa_id',
        'user_id',
    ];

    public function getCodigoUrlAttribute(){
        return url('leitura/qrcode?c=').$this->chave;
        /////oi
    }

    public function disciplina(){
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }

    public function cicloEtapa()
    {
        return $this->belongsTo(CicloEtapa::class, 'cicloetapa_id');
    }

    public function colecaoLivro()
    {
        return $this->belongsTo(ColecaoLivros::class, 'colecao_livro_id');
    }


}
