<?php

namespace App\Entities\Questoes;

use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Entities\Questoes\Questoes;
use App\Models\Conteudo;

/**
 * App\Entities\Questoes\QuestaoConteudo
 *
 * @property int $id
 * @property int $questao_id
 * @property int $conteudo_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Conteudo $conteudo
 * @property-read Questoes $questao
 * @method static \Illuminate\Database\Eloquent\Builder|QuestaoConteudo newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestaoConteudo newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestaoConteudo query()
 * @method static \Illuminate\Database\Eloquent\Builder|QuestaoConteudo whereConteudoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestaoConteudo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestaoConteudo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestaoConteudo whereQuestaoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|QuestaoConteudo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class QuestaoConteudo extends Pivot
{
    protected $table = "questoes_conteudos";

    public $incrementing = true;

    protected $fillable = [
        'questao_id',
        'conteudo_id'
    ];

    public function questao()
    {
        return $this->belongsTo(Questoes::class, 'questao_id');
    }

    public function conteudo()
    {
        return $this->belongsTo(Conteudo::class, 'conteudo_id');
    }
}
