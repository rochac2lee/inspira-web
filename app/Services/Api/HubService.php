<?php

namespace App\Services\Api;

use App\Services\Fr\BibliotecaService;

class HubService
{
    private $appCdn;

    public function __construct(BibliotecaService $bibliotecaService){
        $this->appCdn = config('app.cdn');
        $this->bibliotecaService = $bibliotecaService;
    }

    public function categorias(){
        $retorno[] = (object)  [
            'titulo'=> 'Aplicativos',
            'id'    => 105,
            'capa'  => $this->appCdn.'/storage/capa_app_hub/categoria/105.webp'
        ];
        $retorno[] = (object) [
            'titulo'=> 'Jogos',
            'id'    => 103,
            'capa'  => $this->appCdn.'/storage/capa_app_hub/categoria/103.webp'
        ];
        $retorno[] = (object) [
            'titulo'=> 'Simuladores',
            'id'    => 101,
            'capa'  => $this->appCdn.'/storage/capa_app_hub/categoria/101.webp'
        ];
        $ret['data'] = $retorno;
        return (object) $ret;
    }

    public function disciplinas($tipoConteudo){
        //$this->bibliotecaService->disciplinasConteudo($tipoConteudo,'','');
    }

}
