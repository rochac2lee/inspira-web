<?php

namespace App\Models\Altitude;

use App\Models\Conteudo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Aula extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'curso_id',
        'user_id',
        'titulo',
        'descricao',
        'ordem',
    ];

    public function conteudo(){
        return $this->belongsToMany(Conteudo::class,'conteudo_aula','aula_id','conteudo_id')
            ->withPivot('conteudo_id', 'curso_id', 'aula_id')
            ->where('conteudos.status',1)->orWhere('tipo', 21)
            ->orderByRaw('ISNULL(conteudo_aula.ordem), conteudo_aula.ordem ASC');
    }
}
