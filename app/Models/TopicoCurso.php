<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\TopicoCurso
 *
 * @property int $id
 * @property int $curso_id
 * @property int $user_id
 * @property string $titulo
 * @property string $descricao
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ComentarioTopicoCurso[] $comentarios
 * @property-read int|null $comentarios_count
 * @property-read \App\Models\Curso $curso
 * @property-read mixed $ultimo_comentario
 * @property-read mixed $visualizacoes
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TopicoCurso newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TopicoCurso newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TopicoCurso query()
 * @method static \Illuminate\Database\Eloquent\Builder|TopicoCurso whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicoCurso whereCursoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicoCurso whereDescricao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicoCurso whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicoCurso whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicoCurso whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicoCurso whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TopicoCurso whereUserId($value)
 * @mixin \Eloquent
 */
class TopicoCurso extends Model
{
    protected $table = 'topico_curso';

    //Preenchiveis
    protected $fillable = [
        'curso_id',
        'user_id',
        'titulo',
        'descricao',
        'status'
    ];

    // Protegidos
    protected $hidden = [
    ];

    //Padrões
    protected $attributes = [
        'descricao' => '',
        'status' => 0
    ];

    public function comentarios()
    {

        return $this->hasMany(ComentarioTopicoCurso::class, 'topico_curso_id');
    }

    public function getUltimoComentarioAttribute()
    {
        return null;
        //return \App\ComentarioTopicoCurso::where([['topico_curso_id', '=', $this->id]])->with('user')->first();
    }

    public function getVisualizacoesAttribute()
    {
        return null;
        //return \App\Metrica::where('titulo', '=', 'Visualização tópico - ' . $this->id)->pluck('user_id')->unique('user_id')->count();
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
