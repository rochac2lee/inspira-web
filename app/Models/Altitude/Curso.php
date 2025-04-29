<?php

namespace App\Models\Altitude;

use App\Models\Escola;
use App\Models\Instituicao;
use App\Models\Trilha;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Curso extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'instituicao_id',
        'escola_id',
        'user_id',
        'ciclo_id',
        'cicloetapa_id',
        'disciplina_id',
        'tag',
        'titulo',
        'descricao',
        'capa',
        'publicado',
        'permite_biblioteca',
        'tipo_permissao_biblioteca',
        'novo',
        'publicado',
    ];


    /// atributos customizados
    public function getUrlCapaAttribute(){
        return config('app.cdn').'/storage/uploads/cursos/capas/'.$this->capa;
    }


    /// relaçoes
    public function permissaoBiblioteca()
    {
        return $this->hasMany(CursosInstituicaoEscolaBiblioteca::class, 'curso_id');
    }

    public function escolasBiblioteca()
    {
        return $this->belongsToMany(Escola::class,'cursos_instituicao_escola_biblioteca','curso_id','escola_id')
            ->withPivot(['escola_id','instituicao_id', 'publico', 'privado']);
    }

    public function temas()
    {
        return $this->hasMany(Aula::class,'curso_id')->orderByRaw('ISNULL(ordem), ordem ASC')->orderBy('id');
    }

    public function usuario()
    {
        return $this->hasOne(User::class,'id','user_id');
    }

    public function trilhas(){
        return $this->belongsToMany(Trilha::class,'trilhas_cursos','curso_id','trilha_id');
    }

    public function matriculados()
    {
        return $this->belongsToMany(User::class,'curso_aula_usuario_matricula','curso_id','user_id');
    }
}
