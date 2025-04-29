<?php

namespace App\Http\Resources;

use App\Exceptions\LoginInvalidException;
use App\Services\Api\AgendaService;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPermissaoResource extends JsonResource
{
    /**
     * @var AgendaService
     */
    private $agendaService;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $this->agendaService = new AgendaService();
        $permissoes = $this->agendaService->listaPermissao();

        if($request->input('tipo')=='agenda'){
            $permissoes = $this->restricaoAluno($permissoes);
        }
        return $permissoes;
    }

    private function restricaoAluno($per){
        $permissoes = [];
        foreach ($per as $p){
            if($p['permissao'] != 'A'){
                $permissoes[] = $p;
            }
        }
        if(count($permissoes)==0){
            throw new LoginInvalidException();
        }
        return $permissoes;
    }
}
