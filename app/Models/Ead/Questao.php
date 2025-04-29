<?php

namespace App\Models\Ead;

use App\Models\CicloEtapa;
use App\Models\Disciplina;
use App\Models\FrBncc;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Questao extends Model
{
    use SoftDeletes;
    protected $table = 'ead_questao';

    protected $fillable = [

        'user_id',
        'disciplina_id',
        'dificuldade',
        'tipo',
        'codigo',
        'pergunta',
        'alternativa_1',
        'alternativa_2',
        'alternativa_3',
        'alternativa_4',
        'alternativa_5',
        'alternativa_6',
        'alternativa_7',
        'qtd_alternativa',
        'correta',
        'com_linhas',
        'qtd_linhas',
        'resolucao',
        'link_video_resolucao',
        'bncc_id',
        'cicloetapa_id',
        'unidade_tematica',
        'cadastro',
        'revisado',
        'suporte_id',
        'formato_id',
        'tema_id',
        'palavras_chave',
        'fonte',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class);
    }

    public function bncc()
    {
        return $this->belongsTo(FrBncc::class);
    }

    public function cicloEtapa()
    {
       return  $this->belongsTo(CicloEtapa::class,'cicloetapa_id')->join('ciclos','ciclos.id','ciclo_etapas.ciclo_id')
                    ->selectRaw('ciclo_etapas.id, ciclo_etapas.titulo as ciclo_etapa, ciclos.titulo as ciclo')
                    ->where('ciclos.id','<>','1')
                    ->where('ciclos.id','<>','5');
    }

}
