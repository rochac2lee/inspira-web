<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ComentarioTopicoCurso
 *
 * @property int $id
 * @property int $topico_curso_id
 * @property int $user_id
 * @property string $conteudo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TopicoCurso $topico
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTopicoCurso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTopicoCurso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTopicoCurso query()
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTopicoCurso whereConteudo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTopicoCurso whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTopicoCurso whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTopicoCurso whereTopicoCursoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTopicoCurso whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ComentarioTopicoCurso whereUserId($value)
 * @mixin \Eloquent
 */
class ComentarioTopicoCurso extends Model
{
    protected $table = 'comentario_topico_curso';

    //Preenchiveis
    protected $fillable = [
        'topico_curso_id',
        'user_id',
        'conteudo',
    ];


    public function topico()
    {
        return $this->belongsTo(TopicoCurso::class, 'topico_curso_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
