<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



/**
 * App\Models\AlunoCicloEtapa
 *
 * @property int $user_id
 * @property int $ciclo_id
 * @property int $ciclo_etapa_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|AlunoCicloEtapa newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AlunoCicloEtapa newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AlunoCicloEtapa query()
 * @method static \Illuminate\Database\Eloquent\Builder|AlunoCicloEtapa whereCicloEtapaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlunoCicloEtapa whereCicloId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlunoCicloEtapa whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlunoCicloEtapa whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AlunoCicloEtapa whereUserId($value)
 * @mixin \Eloquent
 */
class AlunoCicloEtapa extends Model
{
   //Model da tabela acao_didaticas  ;
   protected $table = 'aluno_ciclo_etapa';

   protected $fillable = [
       'user_id',
       'ciclo_id',
       'ciclo_etapa_id',
   ];
}
