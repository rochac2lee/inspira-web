<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Categoria
 *
 * @property int $id
 * @property int $user_id
 * @property string $titulo
 * @property int $tipo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property int $status_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Conteudo[] $conteudo
 * @property-read int|null $conteudo_count
 * @property-read mixed $tipo_name
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria newQuery()
 * @method static \Illuminate\Database\Query\Builder|Categoria onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria query()
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereTitulo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Categoria whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Categoria withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Categoria withoutTrashed()
 * @mixin \Eloquent
 */
class Categoria extends Model
{
    use SoftDeletes;

    //Preenchiveis
    protected $fillable = [
        'id',
        'user_id',
        'titulo',
        'tipo',
        'status_id',
    ];

    // Tipos de categorias
    // 0 = Geral
    // 1 = Cursos
    // 2 = Conteudos
    // 3 = Aplicacoes
    // 4 = Artigos
    // 5 = Audios


    protected $dates = ['deleted_at'];

    //Padrões
    protected $attributes = [
        'status_id' => 1,
    ];

    // Mutators
    public function getTipoNameAttribute()
    {
        switch ($this->tipo) {
            case 1:
                return "Cursos";
                break;
            case 2:
                return "Conteúdos";
                break;
            case 3:
                return "Aplicações";
                break;
            case 4:
                return "Artigos";
                break;
            case 5:
                return "Áudios";
                break;

        }
    }

    //
    // Relationship
    //

    public function conteudo()
    {
        return $this->belongsToMany(Conteudo::class, 'categoria_id');
    }

    public function aplicacao()
    {
        return $this->belongsToMany('App\Aplicacao', 'categoria_id');
    }

    public function curso()
    {
        return $this->hasMany('App\Curso', 'categoria');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function playlist(){
        return $this->belongsTo(Playlist::class, 'categoria_id', 'id');
    }

    public function audios(){
        return $this->belongsTo(Audio::class, 'categoria_id', 'id');
    }

    public function albuns(){
        return $this->belongsTo(Album::class, 'categoria', 'id');
    }

    public function status()
    {
        return $this->hasMany('App\Status');
    }
}
